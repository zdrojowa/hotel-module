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
                    <b-form-group label="Logo">
                        <media-selector extensions="svg" @media-selected="select"></media-selector>
                    </b-form-group>

                    <b-img v-if="logo" thumbnail fluid :src="logo"></b-img>

                    <b-button v-if="logo" type="button" variant="danger" @click="remove">Usuń</b-button>
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

            <div class="col-md-12">
                <div class="form-group">
                    <label>Adres</label>
                    <input type="text" class="form-control" name="address" placeholder="Wpisz adres" v-model.lazy="address">
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Latitude</label>
                    <input type="text" class="form-control" name="coordinates[latitude]" placeholder="Wpisz latitude" v-model.lazy="coordinates.latitude">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Longitude</label>
                    <input type="text" class="form-control" name="coordinates[longitude]" placeholder="Wpisz longitude" v-model.lazy="coordinates.longitude">
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'wellness',
        props : ['_id'],

        data() {
            return {
                hotels: [],
                id: 0,
                name: '',
                hotel: {},
                logo: '',
                phone: '',
                mail: '',
                address: '',
                coordinates: {latitude: '', longitude: ''},
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
                return this.id ? ('/dashboard/hotels-wellness/' + this.id) : '/dashboard/hotels-wellness/store';
            }
        },

        methods: {

            select: function(url) {
                this.logo = url;
            },

            remove: function() {
                this.logo = '';
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
                        self.getWellness();
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

            getWellness: function() {
                let self = this;
                if (self._id) {
                    axios.get('/api/hotels/wellness?id=' + self._id)
                        .then(res => {
                            self.id          = res.data._id;
                            self.name        = res.data.name;
                            self.logo        = res.data.logo;
                            self.phone       = res.data.phone;
                            self.mail        = res.data.mail;
                            self.address     = res.data.address;
                            self.coordinates = res.data.coordinates;

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
                    formData.append('logo', this.logo);
                    formData.append('phone', this.phone);
                    formData.append('mail', this.mail);
                    formData.append('address', this.address);
                    formData.append('coordinates[latitude]', this.coordinates.latitude);
                    formData.append('coordinates[longitude]', this.coordinates.longitude);

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
