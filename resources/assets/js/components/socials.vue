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
                    <label>Facebook</label>
                    <input type="text" class="form-control" name="facebook" placeholder="Wpisz link do facebook" v-model.lazy="facebook">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Facebook ID Strony</label>
                    <input type="number" class="form-control" name="facebook_page_id" placeholder="Wpisz ID strony facebook" v-model.lazy="facebook_page_id">
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Twitter</label>
                    <input type="text" class="form-control" name="twitter" placeholder="Wpisz link do Twitter" v-model.lazy="twitter">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Linkedin</label>
                    <input type="text" class="form-control" name="linkedin" placeholder="Wpisz link do Linkedin" v-model.lazy="linkedin">
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Instagram</label>
                    <input type="text" class="form-control" name="instagram" placeholder="Wpisz link do Instagram" v-model.lazy="instagram">
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'socials',
        props : ['_id'],

        data() {
            return {
                facebook: '',
                facebook_page_id: 0,
                twitter: '',
                linkedin: '',
                instagram: '',
            };
        },

        created() {
            this.getHotel();
        },

        methods: {

            getHotel: function() {
                let self = this;

                axios.get('/api/hotels?id=' + self._id)
                .then(res => {
                    self.facebook         = res.data.facebook;
                    self.facebook_page_id = res.data.facebook_page_id;
                    self.twitter          = res.data.twitter;
                    self.linkedin         = res.data.linkedin;
                    self.instagram        = res.data.instagram;
                }).catch(err => {
                    console.log(err)
                })
            },

            save: function(e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('facebook', this.facebook);
                formData.append('facebook_page_id', this.facebook_page_id);
                formData.append('twitter', this.twitter);
                formData.append('linkedin', this.linkedin);
                formData.append('instagram', this.instagram);

                axios.post('/dashboard/hotels/' + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    this.$bvToast.toast('Sociale zaktualizowane', {
                        title: `Sociale`,
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
