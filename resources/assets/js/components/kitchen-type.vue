<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <b-button type="button" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Nazwa</label>
                    <input type="text" :class="getInputClass('name')" name="name" placeholder="Wpisz nazwe" v-model.lazy="name">
                    <small v-if="hasError('name')" class="error mt-2 text-danger">{{ errors.name[0] }}</small>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Tytuł</label>
                    <input type="text" :class="getInputClass('title')" name="title" placeholder="Wpisz tytuł" v-model.lazy="title">
                    <small v-if="hasError('title')" class="error mt-2 text-danger">{{ errors.title[0] }}</small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'kitchen-type',
        props : {
            _id: {
                type: String,
                default: ''
            }
        },

        data() {
            return {
                id: 0,
                name: '',
                title: '',
                errors: {
                    name: {},
                    title: {}
                }
            };
        },

        created() {
            this.getType();
        },

        computed: {

            url() {
                return this.id ? ('/dashboard/hotels-kitchen-type/' + this.id) : '/dashboard/hotels-kitchen-type/store';
            }
        },

        methods: {

            hasError: function(key) {
                return this.errors[key].length > 0;
            },

            getInputClass: function(key) {
                let className = 'form-control ';
                if (this.hasError(key)) {
                    className += 'is-invalid';
                } else {
                    if (this[key]) {
                        className += 'is-valid';
                    }
                }
                return className;
            },

            getType: function() {
                let self = this;
                if (self._id) {
                    axios.get('/api/hotels/kitchen-types?id=' + self._id)
                        .then(res => {
                            self.id    = res.data.id;
                            self.name  = res.data.name;
                            self.title = res.data.title;
                        }).catch(err => {
                        console.log(err)
                    })
                }
            },

            validate: function(e) {
                if (this.name) {
                    this.errors.name = {};
                    if (this.title) {
                        this.errors.title = {};
                        return true;
                    } else {
                        this.errors.title = ['To pole jest wymagane'];
                    }
                } else {
                    this.errors.name = ['To pole jest wymagane'];
                }
                return false;
            },

            save: function(e) {
                e.preventDefault();

                if (this.validate) {
                    let formData = new FormData();
                    formData.append('_method', this.id ? 'PUT' : 'POST');
                    formData.append('name', this.name);
                    formData.append('title', this.title);

                    axios.post(this.url, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(res => {
                        window.location = res.data.redirect;
                    }).catch(err => {
                        console.log(err);
                    });
                } else {
                    return false;
                }
            }
        },

        watch: {
            name() {
                this.validate();
            },
            title() {
                this.validate();
            }
        }
    }
</script>
