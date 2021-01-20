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
            <label>Hotel</label>
            <multiselect
                v-model.lazy="hotel"
                :options="hotels"
                track-by="_id"
                label="name"
                placeholder="Wybierz hotel"
            ></multiselect>
          </b-col>
          <b-col>
            <label>Kuchnia</label>
            <multiselect
                v-model.lazy="kitchen"
                :options="kitchens"
                track-by="_id"
                label="name"
                placeholder="Wybierz kuchnie"
            ></multiselect>
          </b-col>
        </b-row>

        <b-row v-for="lang in langs" class="my-2" :key="lang.key">
          <b-col>
            <label>Opis do hotelu ({{ lang.name }})</label>
            <b-form-textarea
                v-model="hotel_description[lang.key]"
                placeholder="Wpisz opis dla sugerowanego hotelu"
                rows="3"
            ></b-form-textarea>
          </b-col>
          <b-col>
            <label>Opis do kuchni ({{ lang.name }})</label>
            <b-form-textarea
                v-model="kitchen_description[lang.key]"
                placeholder="Wpisz opis dla sugerowanej kuchni"
                rows="3"
            ></b-form-textarea>
          </b-col>
        </b-row>
    </div>
</template>

<script>

    export default {
        name: 'suggestions',
        props : ['_id'],

        data() {
            return {
                langs: [],
                hotels: [],
                kitchens: [],
                hotel: {},
                hotel_description: {},
                kitchen: {},
                kitchen_description: {},
            };
        },

        created() {
            this.getLangs()
        },

        methods: {

            getLangs: function() {
              axios.get('/dashboard/languages/get')
              .then(res => {
                this.langs = res.data
                this.getHotels()
              }).catch(err => {
                console.log(err)
              })
            },

            getHotels: function() {
              axios.get('/api/hotels')
                .then(res => {
                  this.hotels = res.data
                  this.getKitchens()
                }).catch(err => {
                  console.log(err)
              })
            },

            getKitchens: function() {
                axios.get('/api/hotels/kitchen')
                  .then(res => {
                      this.kitchens = res.data
                      this.getSuggestions()
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

            getSuggestions: function() {
                axios.get('/api/hotels?id=' + this._id)
                .then(res => {
                    if (res.data.suggest_hotel) {
                      this.hotel = this.getItem(this.hotels, '_id', res.data.suggest_hotel)
                    }
                    if (res.data.suggest_kitchen) {
                        this.kitchen = this.getItem(this.kitchens, '_id', res.data.suggest_kitchen)
                    }
                    if (res.data.suggest_hotel_description) {
                      this.hotel_description = res.data.suggest_hotel_description
                    }
                    if (res.data.suggest_kitchen_description) {
                      this.kitchen_description = res.data.suggest_kitchen_description
                    }
                    this.checkLangs()
                }).catch(err => {
                    console.log(err)
                    this.checkLangs()
                })
            },

            checkLangs: function() {
              this.langs.forEach(lang => {
                if (!(lang.key in this.hotel_description)) {
                  this.hotel_description[lang.key] = ''
                }
                if (!(lang.key in this.kitchen_description)) {
                  this.kitchen_description[lang.key] = ''
                }
              })
            },

            save: function(e) {
                e.preventDefault()

                let formData = new FormData()
                formData.append('_method', 'PUT')
                formData.append('suggest_hotel', this.hotel ? this.hotel._id : null)
                formData.append('suggest_kitchen', this.kitchen ? this.kitchen._id : null)
                formData.append('suggest_hotel_description', JSON.stringify(this.hotel_description))
                formData.append('suggest_kitchen_description', JSON.stringify(this.kitchen_description))

                axios.post('/dashboard/hotels/' + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    this.$bvToast.toast('Propozycje zaktualizowane', {
                        title: `Propozycje`,
                        variant: 'success',
                        solid: true
                    })
                }).catch(err => {
                    this.$bvToast.toast(err, {
                        title: `Błąd`,
                        variant: 'danger',
                        solid: true
                    })
                })
            }
        }
    }
</script>
