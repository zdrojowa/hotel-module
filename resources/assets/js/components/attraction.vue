<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <b-button type="button" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>

        <div class="row mt-3">
            <b-input-group prepend="Czas(min)" class="col-md-6">
                <b-form-input v-model.lazy="time" type="number"></b-form-input>
            </b-input-group>
            <b-input-group prepend="Cena" class="col-md-6">
                <b-form-input v-model.lazy="price" type="number"></b-form-input>
            </b-input-group>
        </div>

        <div class="row mt-3">
            <b-input-group prepend="Waga(kg)" class="col-md-6">
                <b-form-input v-model.lazy="weight" type="number"></b-form-input>
            </b-input-group>
            <b-input-group prepend="Wiek" class="col-md-6">
                <b-form-input v-model.lazy="years" type="number"></b-form-input>
            </b-input-group>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'attraction',
        props : ['_id'],

        data() {
            return {
                time: 0,
                price: 0,
                weight: 0,
                years: 0
            };
        },

        created() {
            this.getItem();
        },

        methods: {

            getItem: function() {
                let self = this;
                if (self._id) {
                    axios.get('/api/hotels-attraction?id=' + self._id)
                    .then(res => {
                        self.time   = res.data.time;
                        self.price  = res.data.price;
                        self.weight = res.data.weight;
                        self.years  = res.data.years;
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
                formData.append('time', this.time);
                formData.append('price', this.price);
                formData.append('weight', this.weight);
                formData.append('years', this.years);

                axios.post('/dashboard/hotels-attraction/' + self._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    this.$bvToast.toast('Atrakcje zaktualizowane', {
                        title: `SPA`,
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
