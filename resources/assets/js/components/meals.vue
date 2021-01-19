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
            <label>Śniadanie</label>
            <b-form-input
                type="text"
                placeholder="Wpisz godziny śniadania"
                v-model.lazy="breakfast"
            ></b-form-input>
          </b-col>
          <b-col>
            <label>Cena śniadania</label>
            <b-form-input
                type="number"
                placeholder="Wpisz cene śniadania"
                v-model.lazy="prices.breakfast"
            ></b-form-input>
          </b-col>
        </b-row>

        <b-row>
          <b-col>
            <label>Obiad</label>
            <b-form-input
                type="text"
                placeholder="Wpisz godziny obiadu"
                v-model.lazy="lunch"
            ></b-form-input>
          </b-col>
          <b-col>
            <label>Cena obiadu</label>
            <b-form-input
                type="number"
                placeholder="Wpisz cene śniadania"
                v-model.lazy="prices.lunch"
            ></b-form-input>
          </b-col>
        </b-row>

        <b-row>
          <b-col>
            <label>Obiadokolacja</label>
            <b-form-input
                type="text"
                placeholder="Wpisz godziny obiadokolacji"
                v-model.lazy="dinner"
            ></b-form-input>
          </b-col>
          <b-col>
            <label>Cena obiadokolacji</label>
            <b-form-input
                type="number"
                placeholder="Wpisz cene obiadokolacji"
                v-model.lazy="prices.dinner"
            ></b-form-input>
          </b-col>
        </b-row>
    </div>
</template>

<script>

    export default {
        name: 'meals',
        props : ['_id'],

        data() {
            return {
                breakfast: '',
                lunch: '',
                dinner: '',
                prices: {
                  breakfast: 0,
                  lunch: 0,
                  dinner: 0
                }
            };
        },

        created() {
            this.getKitchen()
        },

        methods: {

            getKitchen: function() {
                axios.get('/api/hotels/kitchen?id=' + this._id)
                .then(res => {
                  this.breakfast = res.data.breakfast
                  this.lunch     = res.data.lunch
                  this.dinner    = res.data.dinner

                  if (res.data.prices != null) {
                    this.prices = res.data.prices
                  }

                }).catch(err => {
                    console.log(err)
                })
            },

            save: function(e) {
                e.preventDefault()
                let formData = new FormData()
                formData.append('_method', 'PUT')
                formData.append('breakfast', this.breakfast)
                formData.append('lunch', this.lunch)
                formData.append('dinner', this.dinner)
                formData.append('prices', JSON.stringify(this.prices))

                axios.post('/dashboard/hotels-kitchen/' + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    this.$bvToast.toast('Posiłki zaktualizowane', {
                      title: `Posiłki`,
                      variant: 'success',
                      solid: true
                    })
                  }).catch(err => {
                    this.$bvToast.toast(err, {
                      title: `Błąd`,
                      variant: 'danger',
                      solid: true
                    })
                });
            }
        }
    }
</script>
