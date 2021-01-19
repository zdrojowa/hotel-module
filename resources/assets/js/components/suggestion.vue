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
                <label>Hotel</label>
                <multiselect
                    v-model.lazy="hotel"
                    :options="hotels"
                    track-by="_id"
                    label="name"
                    placeholder="Wybierz hotel"
                ></multiselect>
              </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Kuchnia</label>
                    <multiselect
                        v-model.lazy="kitchen"
                        :options="kitchens"
                        track-by="_id"
                        label="name"
                        placeholder="Wybierz kuchnie"
                    ></multiselect>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'suggestions',
        props : ['_id'],

        data() {
            return {
                hotels: [],
                kitchens: [],
                hotel: {},
                kitchen: {},
            };
        },

        created() {
            this.getHotels()
        },

        methods: {

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
                if (this._id) {
                    axios.get('/api/hotels?id=' + this._id)
                    .then(res => {
                        if (res.data.suggest_hotel) {
                          this.hotel = this.getItem(this.hotels, '_id', res.data.suggest_hotel)
                        }
                        if (res.data.suggest_kitchen) {
                            this.kitchen = this.getItem(this.kitchens, '_id', res.data.suggest_kitchen)
                        }
                    }).catch(err => {
                        console.log(err)
                    })
                }
            },

            save: function(e) {
                e.preventDefault()

                let formData = new FormData()
                formData.append('_method', 'PUT')
                formData.append('suggest_hotel', this.hotel ? this.hotel._id : null)
                formData.append('suggest_kitchen', this.kitchen ? this.kitchen._id : null)

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
