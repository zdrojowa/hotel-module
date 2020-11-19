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
                    <input type="text" class="form-control" name="phone" placeholder="Wpisz telefon" v-model.lazy="phone">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Mail</label>
                    <input type="text" class="form-control" name="mail" placeholder="Wpisz mail" v-model.lazy="mail">
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'contact',
        props: {
            _id: {
                required: true,
                type: String
            },
            url_get: {
                required: true,
                type: String
            },
            url_post: {
                required: true,
                type: String
            },
            prefix: {
                required: false,
                type: String,
                default: ''
            },
        },


        data() {
            return {
                phone: '',
                mail: ''
            };
        },

        created() {
            this.getItem()
        },

        methods: {

            getItem: function() {
                if (this._id) {
                    axios.get(this.url_get + '?id=' + this._id)
                    .then(res => {
                        if (res.data[this.prefix + 'phone'] != null) {
                            this.phone = res.data[this.prefix + 'phone']
                        }
                        if (res.data[this.prefix + 'mail'] != null) {
                            this.mail = res.data[this.prefix + 'mail']
                        }
                    }).catch(err => {
                        console.log(err)
                    })
                }
            },

            save: function(e) {

                let formData = new FormData()
                formData.append('_method','PUT')
                formData.append(this.prefix + 'phone', this.phone)
                formData.append(this.prefix + 'mail', this.mail)

                axios.post(this.url_post + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    this.$bvToast.toast('Informacja kontaktowa zaktualizowana', {
                        title: `Informacja kontaktowa`,
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
