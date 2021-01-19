<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <b-button type="button" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>

        <b-row>
          <b-col>
            <label>Nazwa</label>
            <b-form-input
                type="text"
                :state="name.length > 0"
                placeholder="Wpisz nazwe"
                v-model.lazy="name"
                description="To pole jest wymagane"
            ></b-form-input>
          </b-col>
          <b-col>
            <label>Hotel</label>
            <multiselect
                v-model.lazy="hotel"
                :options="hotels"
                track-by="_id"
                label="name"
                placeholder="Wybierz hotel"
            ></multiselect>
          </b-col>
        </b-row>

        <b-row class="my-1">
          <b-col>
            <label>Typ</label>
            <multiselect
                v-model.lazy="type"
                :options="types"
                track-by="_id"
                label="title"
                placeholder="Wybierz typ"
            ></multiselect>
          </b-col>
        </b-row>

        <b-row class="my-1">
          <b-col>
            <label>Telefon</label>
            <b-form-input
                type="text"
                placeholder="Wpisz telefon"
                v-model.lazy="phone"
            ></b-form-input>
          </b-col>
          <b-col>
            <label>Mail</label>
            <b-form-input
                type="text"
                placeholder="Wpisz mail"
                v-model.lazy="mail"
            ></b-form-input>
          </b-col>
          <b-col>
            <label>Godziny otwarcia cafe</label>
            <b-form-input
                type="text"
                placeholder="Wpisz godziny otwarcia cafe"
                v-model.lazy="cafe_work_hours"
            ></b-form-input>
          </b-col>
        </b-row>

        <b-row class="my-1">
          <b-col>
            <b-form-group label="Logo">
              <media-selector extensions="svg" @media-selected="select"></media-selector>
            </b-form-group>

            <b-img v-if="logo" thumbnail fluid :src="logo"></b-img>

            <b-button v-if="logo" type="button" variant="danger" @click="remove">Usuń</b-button>
          </b-col>
        </b-row>
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
                cafe_work_hours: '',
                work_days: ''
            };
        },

        created() {
            this.getHotels()
        },

        computed: {

            url() {
                return this.id ? ('/dashboard/hotels-kitchen/' + this.id) : '/dashboard/hotels-kitchen/store'
            }
        },

        methods: {

            select: function(url) {
                this.logo = url
            },

            remove: function() {
                this.logo = ''
            },

            hasError: function(key) {
                return this.errors[key].length > 0
            },

            getHotels: function() {
                axios.get('/api/hotels')
                  .then(res => {
                      this.hotels = res.data
                      this.getTypes()
                  }).catch(err => {
                    console.log(err)
                })
            },

            getTypes: function() {
                axios.get('/api/hotels/kitchen-types')
                  .then(res => {
                      this.types = res.data
                      this.getKitchen()
                  }).catch(err => {
                    console.log(err)
                })
            },

            getItem: function(arr, key, val) {
                let item = val

                arr.forEach(it => {
                    if (it[key] === val) {
                        item = it
                    }
                })

                return item
            },

            getKitchen: function() {
                if (this._id) {
                    axios.get('/api/hotels/kitchen?id=' + this._id)
                    .then(res => {
                      this.id              = res.data._id
                      this.name            = res.data.name
                      this.logo            = res.data.logo
                      this.phone           = res.data.phone
                      this.mail            = res.data.mail
                      this.cafe_work_hours = res.data.cafe_work_hours

                      this.hotel = this.getItem(this.hotels, '_id', res.data.hotel)
                      this.type  = this.getItem(this.types, '_id', res.data.type)

                    }).catch(err => {
                        console.log(err)
                    })
                }
            },

            save: function(e) {
                e.preventDefault()

                if (this.name.length) {
                    let formData = new FormData()
                    formData.append('_method', this.id ? 'PUT' : 'POST')
                    formData.append('name', this.name)
                    formData.append('hotel', this.hotel._id)
                    formData.append('type', this.type._id)
                    formData.append('logo', this.logo)
                    formData.append('phone', this.phone)
                    formData.append('mail', this.mail)
                    formData.append('cafe_work_hours', this.cafe_work_hours)

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
        }
    }
</script>
