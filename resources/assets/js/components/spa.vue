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
                    <label>Telefon</label>
                    <input type="text" class="form-control" name="phone" placeholder="Wpisz telefon" v-model.lazy="spa_phone">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Mail</label>
                    <input type="text" class="form-control" name="mail" placeholder="Wpisz mail" v-model.lazy="spa_mail">
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'spa',
        props : ['_id'],

        data() {
            return {
                spa_phone: '',
                spa_mail: ''
            };
        },

        created() {
            this.getItem();
        },

        methods: {

            getItem: function() {
                let self = this;
                if (self._id) {
                    axios.get('/api/hotels?id=' + self._id)
                    .then(res => {
                        self.spa_phone = res.data.spa_phone;
                        self.spa_mail  = res.data.spa_mail;
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
                formData.append('spa_phone', this.spa_phone);
                formData.append('spa_mail', this.spa_mail);

                axios.post('/dashboard/hotels/' + self._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    this.$bvToast.toast('SPA zaktualizowane', {
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
