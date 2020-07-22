<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <b-button type="button" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>

        <div v-for="lang in langs" class="row">
            <div class="form-group col-sm-12">
                <label>{{ lang.name }}</label>
                <input type="text" v-model="costs[lang.key]" class="form-control">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'cost',
        props : ['_id'],

        data() {
            return {
                langs: [],
                costs: {}
            };
        },

        created() {
            this.getLangs();
        },

        methods: {

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
                    axios.get('/api/hotels/apartments?id=' + self._id)
                        .then(res => {
                            if (res.data.costs != null) {
                                self.costs = res.data.costs;
                                self.checkLangs();
                            }
                            self.checkLangs();
                        }).catch(err => {
                        console.log(err);
                        self.checkLangs();
                    })
                }
                self.checkLangs();
            },

            checkLangs: function() {
                let self = this;
                self.langs.forEach(lang => {
                    if (!(lang.key in self.costs)) {
                        self.costs[lang.key] = '';
                    }
                });
            },

            save: function(e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append('_method','PUT');
                formData.append('costs', JSON.stringify(this.costs));

                axios.post('/dashboard/hotels-apartments/' + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(res => {
                        this.$bvToast.toast('Ceny zaktualizowane', {
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
