<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <b-button type="button" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>

        <div class="row">
            <div class="form-group col-sm-12">
                <div class="form-group">
                    <label>Hotele</label>
                    <multiselect v-model.lazy="hotels" :options="hotelsOptions" track-by="id" label="name" placeholder="Wybierz hotele" :multiple="true" :searchable="true"></multiselect>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-sm-12">
                <div class="form-group">
                    <label>Wellness</label>
                    <multiselect v-model.lazy="wellness" :options="wellnessOptions" track-by="id" label="name" placeholder="Wybierz Wellness" :multiple="true" :searchable="true"></multiselect>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-sm-12">
                <div class="form-group">
                    <label>Kuchnia</label>
                    <multiselect v-model.lazy="kitchens" :options="kitchensOptions" track-by="id" label="name" placeholder="Wybierz kuchni" :multiple="true" :searchable="true"></multiselect>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'objects',
        props : ['_id'],

        data() {
            return {
                hotels: [],
                wellness: [],
                kitchens: [],
                hotelsOptions: [],
                wellnessOptions: [],
                kitchensOptions: [],
            };
        },

        created() {
            this.getHotels();
        },

        methods: {

            getHotels: function() {
                let self = this;

                axios.get('/api/hotels')
                    .then(res => {
                        self.hotelsOptions.push({id: 0, name: 'Wszystkie'});
                        res.data.forEach(item => {
                            self.hotelsOptions.push({id: item._id, name: item.name});
                        });
                        this.getWellness();
                    }).catch(err => {
                    console.log(err)
                })
            },

            getWellness: function() {
                let self = this;

                axios.get('/api/hotels/wellness')
                .then(res => {
                    self.wellnessOptions.push({id: 0, name: 'Wszystkie'});
                    res.data.forEach(item => {
                        self.wellnessOptions.push({id: item._id, name: item.name});
                    });
                    this.getKitchens();
                }).catch(err => {
                    console.log(err)
                })
            },

            getKitchens: function() {
                let self = this;

                axios.get('/api/hotels/kitchen')
                    .then(res => {
                        self.kitchensOptions.push({id: 0, name: 'Wszystkie'});
                        res.data.forEach(item => {
                            self.kitchensOptions.push({id: item._id, name: item.name});
                        });
                        this.getOffer();
                    }).catch(err => {
                    console.log(err)
                })
            },

            getOffer: function() {
                let self = this;
                if (self._id) {
                    axios.get('/api/offers?id=' + self._id)
                    .then(res => {
                        if (res.data.hotels != null) {
                            res.data.hotels.forEach(hotel => {
                                self.hotelsOptions.forEach(item => {
                                    if (item.id === hotel) {
                                        self.hotels.push(item);
                                    }
                                });
                            });
                        }
                        if (res.data.wellness != null) {
                            res.data.wellness.forEach(wellness => {
                                self.wellnessOptions.forEach(item => {
                                    if (item.id === wellness) {
                                        self.wellness.push(item);
                                    }
                                });
                            });
                        }
                        if (res.data.kitchens != null) {
                            res.data.kitchens.forEach(kitchen => {
                                self.kitchensOptions.forEach(item => {
                                    if (item.id === kitchen) {
                                        self.kitchens.push(item);
                                    }
                                });
                            });
                        }
                    }).catch(err => {
                        console.log(err);
                    })
                }
            },

            save: function(e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append('_method','PUT');

                let hotels = [];
                this.hotels.forEach(item => {
                    if (item.id === 0) {
                        hotels = [0];
                    }
                    if (hotels[0] !== 0) {
                        hotels.push(item.id);
                    }
                });
                let wellness = [];
                this.wellness.forEach(item => {
                    if (item.id === 0) {
                        wellness = [0];
                    }
                    if (wellness[0] !== 0) {
                        wellness.push(item.id);
                    }
                });
                let kitchens = [];
                this.kitchens.forEach(item => {
                    if (item.id === 0) {
                        kitchens = [0];
                    }
                    if (kitchens[0] !== 0) {
                        kitchens.push(item.id);
                    }
                });

                formData.append('hotels', JSON.stringify(hotels));
                formData.append('wellness', JSON.stringify(wellness));
                formData.append('kitchens', JSON.stringify(kitchens));

                axios.post('/dashboard/offers/' + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    this.$bvToast.toast('Objekty zaktualizowane', {
                        title: `Objekty`,
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
