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
                    <label>Kuchnia</label>
                    <multiselect v-model.lazy="kitchen" :options="kitchens" track-by="_id" label="name" placeholder="Wybierz kuchnie"></multiselect>
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
                kitchens: [],
                kitchen: {},
            };
        },

        created() {
            this.getKitchens();
        },

        methods: {

            getKitchens: function() {
                let self = this;

                axios.get('/api/hotels/kitchen')
                    .then(res => {
                        self.kitchens = res.data;
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
                    axios.get('/api/hotels?id=' + self._id)
                    .then(res => {
                        if (res.data.suggest_kitchen) {
                            self.kitchen = self.getItem(self.kitchens, '_id', res.data.suggest_kitchen);
                        }
                    }).catch(err => {
                        console.log(err)
                    })
                }
            },

            save: function(e) {
                e.preventDefault();

                let self = this;

                let formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('suggest_kitchen', this.kitchen ? this.kitchen._id : null);

                axios.post('/dashboard/hotels/' + self._id, formData, {
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
                });
            }
        }
    }
</script>
