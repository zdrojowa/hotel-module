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

            <div class="col-md-12">
                <div class="form-group">
                    <b-form-group label="Obrazek">
                        <media-selector extensions="jpg,jpeg,png" @media-selected="select"></media-selector>
                    </b-form-group>

                    <b-img v-if="image" thumbnail fluid :src="image"></b-img>

                    <b-button v-if="image" type="button" variant="danger" @click="remove">Usuń</b-button>
                </div>
            </div>

        </div>

        <div v-for="lang in langs">
            <b-input-group :prepend="'Tytuł (' + lang.name + ')'" class="row mt-3">
                <b-form-input v-model.lazy="titles[lang.key]"></b-form-input>
            </b-input-group>
            <b-input-group :prepend="'Uwagi (' + lang.name + ')'" class="row mt-3">
                <b-form-textarea v-model.lazy="info[lang.key]" rows="5"></b-form-textarea>
            </b-input-group>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'rent',
        props : ['_id'],

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
                return this.id ? ('/dashboard/hotels-rent/' + this.id) : '/dashboard/hotels-rent/store';
            }
        },

        methods: {

            select: function(url) {
                this.image = url;
            },

            remove: function() {
                this.image = '';
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
                        self.getRent();
                    }).catch(err => {
                    console.log(err)
                })
            },

            getLangs: function() {
                axios.get('/dashboard/languages/get')
                    .then(res => {
                        this.langs = res.data;
                        this.getRent();
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

            getRent: function() {
                let self = this;
                if (self._id) {
                    axios.get('/api/hotels/rent?id=' + self._id)
                        .then(res => {
                            self.id     = res.data._id;
                            self.name   = res.data.name;
                            self.image  = res.data.image;
                            self.titles = res.data.titles;
                            self.info   = res.data.info;

                            self.hotel = self.getItem(self.hotels, '_id', res.data.hotel);

                            self.checkLangs(self.titles);
                            self.checkLangs(self.info);

                    }).catch(err => {
                        console.log(err)
                    })
                }

                self.checkLangs(self.titles);
                self.checkLangs(self.info);
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
                    formData.append('_method', self.id ? 'PUT' : 'POST');
                    formData.append('name', self.name);
                    formData.append('hotel', self.hotel._id);
                    formData.append('image', self.image);
                    formData.append('titles', JSON.stringify(self.titles));
                    formData.append('info', JSON.stringify(self.info));

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
