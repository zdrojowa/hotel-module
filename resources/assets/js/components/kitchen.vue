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
                    <label>Typ</label>
                    <multiselect v-model.lazy="type" :options="types" track-by="_id" label="title" placeholder="Wybierz typ"></multiselect>
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

            <div class="col-md-6">
                <div class="form-group">
                    <label>Godziny otwarcia cafe</label>
                    <input type="text" class="form-control" name="cafe_work_hours" placeholder="Wpisz godziny otwarcia cafe" v-model.lazy="cafe_work_hours">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Śniadanie</label>
                    <input type="text" class="form-control" name="breakfast" placeholder="Wpisz godziny śniadania" v-model.lazy="breakfast">
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Obiad</label>
                    <input type="text" class="form-control" name="lunch" placeholder="Wpisz godziny obiadu" v-model.lazy="lunch">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Obiadokolacja</label>
                    <input type="text" class="form-control" name="dinner" placeholder="Wpisz godziny obiadokolacji" v-model.lazy="dinner">
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'kitchen',
        props : ['_id'],

        data() {
            return {
                hotels: [],
                types: [],
                id: 0,
                name: '',
                hotel: {},
                type: {},
                logo: '',
                phone: '',
                mail: '',
                work_hours: '',
                cafe_work_hours: '',
                work_days: '',
                breakfast: '',
                lunch: '',
                dinner: '',
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
                return this.id ? ('/dashboard/hotels-kitchen/' + this.id) : '/dashboard/hotels-kitchen/store';
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
                        self.getTypes();
                    }).catch(err => {
                    console.log(err)
                })
            },

            getTypes: function() {
                let self = this;

                axios.get('/api/hotels/kitchen-types')
                    .then(res => {
                        self.types = res.data;
                        self.getKitchen();
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

            getKitchen: function() {
                let self = this;
                if (self._id) {
                    axios.get('/api/hotels/kitchen?id=' + self._id)
                    .then(res => {
                        self.id              = res.data._id;
                        self.name            = res.data.name;
                        self.logo            = res.data.logo;
                        self.phone           = res.data.phone;
                        self.mail            = res.data.mail;
                        self.work_hours      = res.data.work_hours;
                        self.cafe_work_hours = res.data.cafe_work_hours;
                        self.work_days       = res.data.work_days;
                        self.breakfast       = res.data.breakfast;
                        self.lunch           = res.data.lunch;
                        self.dinner          = res.data.dinner;

                        self.hotel = self.getItem(self.hotels, '_id', res.data.hotel);
                        self.type  = self.getItem(self.types, '_id', res.data.type);

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
                    formData.append('type', this.type._id);
                    formData.append('logo', this.logo);
                    formData.append('phone', this.phone);
                    formData.append('mail', this.mail);
                    formData.append('work_hours', this.work_hours);
                    formData.append('cafe_work_hours', this.cafe_work_hours);
                    formData.append('work_days', this.work_days);
                    formData.append('breakfast', this.breakfast);
                    formData.append('lunch', this.lunch);
                    formData.append('dinner', this.dinner);

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
