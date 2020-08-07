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
                    <b-form-input type="text" v-model.lazy="name" :state="name.length > 0"></b-form-input>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Pełna nazwa</label>
                    <b-form-input type="text" v-model.lazy="full_name"></b-form-input>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <b-form-group label="Logo">
                        <media-selector extensions="svg" @media-selected="selectLogo"></media-selector>
                    </b-form-group>

                    <b-img v-if="logo" thumbnail fluid :src="logo"></b-img>

                    <b-button v-if="logo" type="button" variant="danger" @click="removeLogo">Usuń</b-button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <b-form-group label="Zdjęcie obiektu">
                        <media-selector extensions="png,jpg,jpeg" @media-selected="selectPhoto"></media-selector>
                    </b-form-group>

                    <b-img v-if="photo" thumbnail fluid :src="photo"></b-img>

                    <b-button v-if="photo" type="button" variant="danger" @click="removePhoto">Usuń</b-button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <b-form-group label="Marker">
                        <media-selector extensions="png" @media-selected="selectMarker"></media-selector>
                    </b-form-group>

                    <b-img v-if="marker" thumbnail fluid :src="marker"></b-img>

                    <b-button v-if="marker" type="button" variant="danger" @click="removeMarker">Usuń</b-button>
                </div>
            </div>

        </div>

        <div class="row mt-2">
            <div class="col-sm-12">
                <b-input-group>
                    <b-input-group-prepend>
                        <b-button @click="star = 0"><b-icon-slash-circle></b-icon-slash-circle></b-button>
                    </b-input-group-prepend>
                    <b-form-rating v-model="star" color="#ff8800"></b-form-rating>
                </b-input-group>
            </div>
        </div>

        <div class="row mt-2">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Rezerwacja</label>
                    <b-form-input type="tel" v-model.lazy="reservation"></b-form-input>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Recepcja</label>
                    <b-form-input type="tel" v-model.lazy="reception"></b-form-input>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Latitude</label>
                    <b-form-input type="number" v-model.lazy="coordinates.latitude"></b-form-input>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Longitude</label>
                    <b-form-input type="number" v-model.lazy="coordinates.longitude"></b-form-input>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Miasto</label>
                    <multiselect v-model.lazy="city" :options="cities" track-by="_id" label="name" placeholder="Wybierz miasto"></multiselect>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Ulica</label>
                    <b-form-input type="text" v-model.lazy="street"></b-form-input>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-sm-12">
                <div class="form-group">
                    <label>Adres</label>
                    <b-form-input type="text" v-model.lazy="address"></b-form-input>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-sm-12">
                <div class="form-group">
                    <label>Jak dojade</label>
                    <b-form-input type="text" v-model.lazy="arrive"></b-form-input>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>E-mail</label>
                    <b-form-input type="email" v-model.lazy="mail"></b-form-input>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Copytight</label>
                    <b-form-input type="text" v-model.lazy="copyright"></b-form-input>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'hotel',
        props : ['_id'],

        data() {
            return {
                cities: [],
                id: 0,
                name: '',
                full_name: '',
                city: {},
                star: 0,
                logo: '',
                photo: '',
                marker: '',
                reception: '',
                reservation: '',
                mail: '',
                address: '',
                coordinates: {latitude: '', longitude: ''},
                street: '',
                arrive: '',
                copyright: ''
            };
        },

        created() {
            this.getCities();
        },

        computed: {

            url() {
                return this.id ? ('/dashboard/hotels/' + this.id) : '/dashboard/hotels/store';
            }
        },

        methods: {

            selectLogo: function(url) {
                this.logo = url;
            },

            removeLogo: function() {
                this.logo = '';
            },

            selectPhoto: function(url) {
                this.photo = url;
            },

            removePhoto: function() {
                this.photo = '';
            },

            selectMarker: function(url) {
                this.marker = url;
            },

            removeMarker: function() {
                this.marker = '';
            },

            getCities: function() {
                let self = this;

                axios.get('/api/cities')
                    .then(res => {
                        self.cities = res.data;
                        self.getHotel();
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

            getHotel: function() {
                let self = this;
                if (self._id) {
                    axios.get('/api/hotels?id=' + self._id)
                    .then(res => {
                        self.id          = res.data._id;
                        self.name        = res.data.name;
                        self.full_name   = res.data.full_name;
                        self.logo        = res.data.logo;
                        self.photo       = res.data.photo;
                        self.marker      = res.data.marker;
                        self.star        = res.data.star;
                        self.reception   = res.data.reception;
                        self.reservation = res.data.reservation;
                        self.mail        = res.data.mail;
                        self.address     = res.data.address;
                        self.coordinates = res.data.coordinates;
                        self.street      = res.data.street;
                        self.arrive      = res.data.arrive;
                        self.copyright   = res.data.copyright;

                        self.city = self.getItem(self.cities, '_id', res.data.city);

                    }).catch(err => {
                        console.log(err)
                    })
                }
            },

            validate: function(e) {
                if (this.name) {
                    return true;
                }
                return false;
            },

            save: function(e) {
                e.preventDefault();

                if (this.validate) {
                    let formData = new FormData();
                    formData.append('_method', this.id ? 'PUT' : 'POST');
                    formData.append('name', this.name);
                    formData.append('full_name', this.full_name);
                    formData.append('city', this.city._id);
                    formData.append('logo', this.logo);
                    formData.append('photo', this.photo);
                    formData.append('marker', this.marker);
                    formData.append('star', this.star);
                    formData.append('reception', this.reception);
                    formData.append('reservation', this.reservation);
                    formData.append('mail', this.mail);
                    formData.append('address', this.address);
                    formData.append('coordinates[latitude]', this.coordinates.latitude);
                    formData.append('coordinates[longitude]', this.coordinates.longitude);
                    formData.append('street', this.street);
                    formData.append('arrive', this.arrive);
                    formData.append('copyright', this.copyright);

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
