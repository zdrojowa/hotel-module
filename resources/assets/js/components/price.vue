<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <b-button type="bytton" variant="success" @click="add">Dodaj</b-button>
            </b-nav-item>
            <b-nav-item>
                <b-button type="bytton" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>

        <div class="row item-conteiner">
            <draggable class="list-group" ghost-class="ghost" :list="prices">
                <div class="list-group-item" v-for="(price, index) in prices" :key="index">
                    <div class="item gallery-item">
                        <div>
                            <button type="button" aria-label="Close" class="close" @click="remove(index)">×</button>
                        </div>
                        <b-form-group
                            label-cols-lg="3"
                            :label="'Wariant ' + (index + 1)"
                            label-size="lg"
                            label-class="font-weight-bold pt-0"
                            class="mb-0"
                        >
                            <b-form-group v-for="lang in langs" :key="index + lang.key"
                              label-cols-sm="3"
                              :label="'Termin(' + lang.name + ')'"
                              label-align-sm="right"
                            >
                                <b-form-input v-model.lazy="price[lang.key]"></b-form-input>
                            </b-form-group>

                            <b-form-group
                                label-cols-sm="3"
                                label="Cena"
                                label-align-sm="right"
                            >
                                <b-form-input v-model.lazy="price.price" type="number"></b-form-input>
                            </b-form-group>
                        </b-form-group>
                    </div>
                </div>
            </draggable>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'price',
        props: {
            _id: {
                required: true,
                type: String
            }
        },

        data() {
            return {
                langs: [],
                prices: []
            };
        },

        created() {
            this.getLangs();
        },

        methods: {

            add: function() {
                let item = {price: 0};
                this.langs.forEach(lang => {
                    item[lang.key] = '';
                });
                this.prices.push(item);
            },

            remove: function(index) {
                this.prices.splice(index, 1);
            },

            getLangs: function() {
                axios.get('/dashboard/languages/get')
                    .then(res => {
                        this.langs = res.data;
                        this.getItem();
                    }).catch(err => {
                    console.log(err)
                })
            },

            getItem: function() {
                let self = this;
                if (self._id) {
                    axios.get('/api/hotels/rent?id=' + self._id)
                        .then(res => {
                            if (res.data.prices != null) {
                                self.prices = res.data.prices;
                                self.checkLangs();
                            }
                    }).catch(err => {
                        console.log(err);
                    })
                }
            },

            checkLangs: function() {
                let self = this;
                self.langs.forEach(lang => {
                    self.prices.forEach(item => {
                        if (!(lang.key in item)) {
                            item[lang.key] = '';
                        }
                    });
                });
            },

            save: function(e) {
                let self = this;
                e.preventDefault();

                let formData = new FormData();
                formData.append('_method','PUT');
                formData.append('prices', JSON.stringify(this.prices));

                axios.post('/dashboard/hotels-rent/' + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(res => {
                        this.$bvToast.toast('Ceny zaktualizowany', {
                            title: `Ceny`,
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
