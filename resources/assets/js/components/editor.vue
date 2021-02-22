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
                    <label>Cena</label>
                    <input type="number" class="form-control" name="price" placeholder="Wpisz cenę" v-model.lazy="price" step="0.01">
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Data publikacji</label>
                </div>
                <div class="form-group">
                    <datetime id="date_from" v-model="date_from" type="date" value-zone="local" :min-datetime="now" format="yyyy-MM-dd"></datetime>
                    <small v-if="hasError('date_from')" class="error mt-2 text-danger">{{ errors.date_from[0] }}</small>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Data zakończenia</label>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <datetime id="date_to" v-model="date_to" type="date" value-zone="local" :min-datetime="date_from" format="yyyy-MM-dd"></datetime>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="button" @click="date_to = ''" class="btn btn-danger">Usuń datę</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Rabat</label>
                    <input type="number" class="form-control" name="discount" placeholder="Wpisz rabat" v-model.lazy="discount" max="100" min="0">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Mimimalna ilość nocy</label>
                    <input type="number" class="form-control" name="min_nights" placeholder="Wpisz minimalną ilość nocy" v-model.lazy="min_nights" max="100" min="0">
                </div>
            </div>

            <div class="col-md-6">
                <b-form-checkbox v-model.lazy="is_aquapark_offer" switch>
                    Oferta aquaparku
                </b-form-checkbox>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'editor',
        props : ['_id'],

        data() {
            return {
                now: '',
                id: 0,
                name: '',
                date_from: '',
                date_to: '',
                discount: 0,
                price: 0,
                min_nights: 0,
                is_aquapark_offer: false,
                errors: {
                    name: {},
                    date_from: {}
                }
            };
        },

        created() {
            this.now = String(new Date().getDate())
            this.getOffer()
        },

        computed: {

            url() {
                return this.id ? ('/dashboard/offers/' + this.id) : '/dashboard/offers/store'
            }
        },

        methods: {

            hasError: function(key) {
                return this.errors[key].length > 0
            },

            getInputClass: function(key) {
                let className = 'form-control '
                if (this.hasError(key)) {
                    className += 'is-invalid'
                } else {
                    if (this[key]) {
                        className += 'is-valid'
                    }
                }
                return className
            },

            getItem: function(arr, key, val) {

                let item = val;

                arr.forEach(it => {
                    if (it[key] === val) {
                        item = it
                    }
                });

                return item
            },

            getOffer: function() {
                let self = this
                if (self._id) {
                    axios.get('/api/offers?id=' + self._id)
                    .then(res => {
                        self.id                 = res.data.id
                        self.name               = res.data.name
                        self.date_from          = res.data.date_from
                        self.is_aquapark_offer  = res.data.is_aquapark_offer
                        self.date_to            = res.data.date_to == null ? '' : res.data.date_to
                        self.discount           = res.data.discount == null ? 0 : res.data.discount
                        self.price              = res.data.price == null ? 0 : res.data.price
                        self.min_nights         = res.data.min_nights == null ? 0 : res.data.min_nights

                    }).catch(err => {
                        console.log(err)
                    })
                }
            },

            validate: function(e) {
                if (this.name) {
                    this.errors.name = {}
                    if (this.date_from) {
                        this.errors.date_from = {}
                       return true;
                    } else {
                        this.errors.date_from = ['To pole jest wymagane']
                    }
                } else {
                    this.errors.name = ['To pole jest wymagane']
                }
                return false
            },

            save: function(e) {
                e.preventDefault()

                if (this.validate) {
                    let formData = new FormData()
                    formData.append('_method', this.id ? 'PUT' : 'POST')
                    formData.append('name', this.name)
                    formData.append('date_from', this.date_from)
                    formData.append('date_to', this.date_to == null ? '' : this.date_to)
                    formData.append('discount', this.discount)
                    formData.append('price', this.price)
                    formData.append('min_nights', this.min_nights)
                    formData.append('is_aquapark_offer', this.is_aquapark_offer)

                    axios.post(this.url, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(res => {
                        window.location = res.data.redirect
                    }).catch(err => {
                        console.log(err)
                    });
                } else {
                    return false
                }
            }
        },

        watch: {
            name() {
                this.validate()
            },

            date_from() {
                this.validate()
            }
        }
    }
</script>
