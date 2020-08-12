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

        <b-card>
            <b-form-group
                label-cols-lg="3"
                label="Video"
                label-size="lg"
                label-class="font-weight-bold pt-0"
                class="mb-0"
            >
                <b-form-group
                    label-cols-sm="3"
                    label="Nazwa"
                    label-align-sm="right"
                >
                    <b-form-input v-model.lazy="video.name"></b-form-input>
                </b-form-group>

                <b-form-group
                    label-cols-sm="3"
                    label="Link"
                    label-align-sm="right"
                >
                    <b-form-input v-model.lazy="video.link"></b-form-input>
                </b-form-group>
            </b-form-group>
        </b-card>
    </div>
</template>

<script>

    export default {
        name: 'socials',
        props : ['_id', 'url_get', 'url_post'],

        data() {
            return {
                facebook: '',
                facebook_page_id: 0,
                twitter: '',
                linkedin: '',
                instagram: '',
                video: {name: '', link: ''},
            };
        },

        created() {
            this.getItem();
        },

        methods: {

            getItem: function() {
                let self = this;

                axios.get(self.url_get + '?id=' + self._id)
                .then(res => {
                    self.facebook         = res.data.facebook;
                    self.facebook_page_id = res.data.facebook_page_id;
                    self.twitter          = res.data.twitter;
                    self.linkedin         = res.data.linkedin;
                    self.instagram        = res.data.instagram;

                    if (res.data.video != null) {
                        self.video = res.data.video;
                    }
                }).catch(err => {
                    console.log(err)
                })
            },

            save: function(e) {
                let self = this;
                e.preventDefault();

                let formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('facebook', self.facebook);
                formData.append('facebook_page_id', self.facebook_page_id);
                formData.append('twitter', self.twitter);
                formData.append('linkedin', self.linkedin);
                formData.append('instagram', self.instagram);
                formData.append('video', JSON.stringify(this.video));

                axios.post(self.url_post + self._id, formData, {
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
