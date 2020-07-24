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
                    <label>Iłość osób</label>
                    <input type="number" class="form-control" placeholder="Wpisz iłość gości" v-model.lazy="guests">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Cena</label>
                    <input type="number" class="form-control" placeholder="Wpisz cenę" v-model.lazy="price" step="0.01">
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Powierzchnia, m<sup>2</sup></label>
                    <input type="number" class="form-control" placeholder="Wpisz powierzchnię" v-model.lazy="square" step="0.01">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Powierzchnia do, m<sup>2</sup></label>
                    <input type="number" class="form-control" placeholder="Wpisz powierzchnię do" v-model.lazy="square_to" step="0.01">
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'apartment',
        props : {
            _id: {
                type: String,
                default: ''
            },
            _hotel: {
                type: String,
                default: ''
            }
        },

        data() {
            return {
                hotels: [],
                id: 0,
                name: '',
                hotel: {},
                guests: 0,
                price: 0,
                square: 0,
                square_to: 0,
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
                return this.id ? ('/dashboard/hotels-apartments/' + this.id) : '/dashboard/hotels-apartments/store';
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

                        self.hotel = self.getItem(self.hotels, '_id', self._hotel);

                        self.getApartment();
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

            getApartment: function() {
                let self = this;
                if (self._id) {
                    axios.get('/api/hotels/apartments?id=' + self._id)
                        .then(res => {
                            self.id        = res.data.id;
                            self.name      = res.data.name;
                            self.guests    = res.data.guests;
                            self.price     = res.data.price;
                            self.square    = res.data.square;
                            self.square_to = res.data.square_to;

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
                    formData.append('guests', this.guests);
                    formData.append('price', this.price);
                    formData.append('square', this.square);
                    formData.append('square_to', this.square_to);

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
