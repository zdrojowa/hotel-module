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

        <div v-for="lang in langs">
            <b-input-group :prepend="'Tytuł (' + lang.name + ')'" class="row mt-3">
                <b-form-input v-model.lazy="titles[lang.key]"></b-form-input>
            </b-input-group>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'kids-club-schedule',
        props : ['_id', '_name'],

        data() {
            return {
                hotels: [],
                langs: [],
                id: 0,
                name: '',
                hotel: {},
                image: '',
                titles: {},
                info: {},
                errors: {
                    name: {}
                }
            };
        },

        created() {
            this.getHotels();
            this.getLangs();
        },

        computed: {

            url() {
                return this._id
                    ? ('/dashboard/hotels-' + this._name + '/' + this._id)
                    : '/dashboard/hotels-' + this._name + '/store';
            }
        },

        methods: {

            select: function(url) {
                this.image = url;
            },

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
                    }).catch(err => {
                    console.log(err)
                })
            },

            getLangs: function() {
                axios.get('/dashboard/languages/get')
                    .then(res => {
                        this.langs = res.data;
                        this.get();
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

            get: function() {
                let self = this;
                if (self._id) {
                    axios.get('/api/hotels-' + self._name + '?id=' + self._id)
                        .then(res => {
                            self.id     = res.data._id;
                            self.name   = res.data.name;
                            self.titles = res.data.titles;

                            self.hotel = self.getItem(self.hotels, '_id', res.data.hotel);

                            self.checkLangs(self.titles);
                    }).catch(err => {
                        console.log(err)
                    })
                }

                self.checkLangs(self.titles);
            },

            checkLangs: function(field) {
                let self = this;
                self.langs.forEach(lang => {
                    if (!(lang.key in field)) {
                        field[lang.key] = '';
                    }
                });
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
                let self = this;
                e.preventDefault();
                if (this.validate) {
                    let formData = new FormData();
                    formData.append('_method', self._id ? 'PUT' : 'POST');
                    formData.append('name', self.name);
                    formData.append('hotel', self.hotel._id);
                    formData.append('titles', JSON.stringify(self.titles));

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
