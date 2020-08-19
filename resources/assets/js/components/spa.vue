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
                    <label>Hotel</label>
                    <multiselect v-model.lazy="hotel" :options="hotels" track-by="_id" label="name" placeholder="Wybierz hotel"></multiselect>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Telefon</label>
                    <input type="text" class="form-control" name="phone" placeholder="Wpisz telefon" v-model.lazy="phone">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Mail</label>
                    <input type="text" class="form-control" name="mail" placeholder="Wpisz mail" v-model.lazy="mail">
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Dni otwarcia</label>
                    <input type="text" class="form-control" name="work_days" placeholder="Wpisz dni otwarcia" v-model.lazy="work_days">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Godziny otwarcia</label>
                    <input type="text" class="form-control" name="work_hours" placeholder="Wpisz godziny otwarcia" v-model.lazy="work_hours">
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'spa',
        props : ['_id'],

        data() {
            return {
                hotels: [],
                id: 0,
                name: '',
                hotel: {},
                phone: '',
                mail: '',
                work_hours: '',
                work_days: '',
                errors: {
                    name: {}
                }
            };
        },

        created() {
            this.getHotels();
        },

        computed: {

            url() {
                return this.id ? ('/dashboard/hotels-spa/' + this.id) : '/dashboard/hotels-spa/store';
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

            getHotels: function() {
                let self = this;

                axios.get('/api/hotels')
                    .then(res => {
                        self.hotels = res.data;
                        self.getSpa();
                    }).catch(err => {
                    console.log(err)
                })
            },

            getItem: function(arr, key, val) {

                let item = val;

                arr.forEach(it => {
                    if (it[key] === val) {
                        item = it;
                    }
                });

                return item;
            },

            getSpa: function() {
                let self = this;
                if (self._id) {
                    axios.get('/api/hotels/spa?id=' + self._id)
                    .then(res => {
                        self.id         = res.data._id;
                        self.name       = res.data.name;
                        self.phone      = res.data.phone;
                        self.mail       = res.data.mail;
                        self.work_hours = res.data.work_hours;
                        self.work_days  = res.data.work_days;
                        self.breakfast  = res.data.breakfast;

                        self.hotel = self.getItem(self.hotels, '_id', res.data.hotel);
                    }).catch(err => {
                        console.log(err)
                    })
                }
            },

            validate: function(e) {
                if (this.name) {
                    this.errors.name = {};
                    return true;
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
                    formData.append('hotel', this.hotel._id);
                    formData.append('phone', this.phone);
                    formData.append('mail', this.mail);
                    formData.append('work_hours', this.work_hours);
                    formData.append('work_days', this.work_days);

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
            }
        }
    }
</script>
