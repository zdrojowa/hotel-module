<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <b-button type="button" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>

        <div class="row">

            <div class="col-md-12">
                <div class="form-group">
                    <b-form-group label="Logo">
                        <media-selector extensions="svg" @media-selected="select"></media-selector>
                    </b-form-group>

                    <b-img v-if="wellness_logo" thumbnail fluid :src="wellness_logo"></b-img>

                    <b-button v-if="wellness_logo" type="button" variant="danger" @click="remove">Usuń</b-button>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

    export default {
        name: 'wellness',
        props : ['_id'],

        data() {
            return {
                wellness_logo: '',
            }
        },

        created() {
            this.getItem()
        },

        methods: {

            select: function(url) {
                this.wellness_logo = url
            },

            remove: function() {
                this.wellness_logo = ''
            },

            getItem: function() {
                if (this._id) {
                    axios.get('/api/hotels?id=' + this._id)
                    .then(res => {
                        this.wellness_logo = res.data.wellness_logo
                    }).catch(err => {
                        console.log(err)
                    })
                }
            },

            save: function(e) {
                let formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('wellness_logo', this.wellness_logo)

                axios.post('/dashboard/hotels/' + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    this.$bvToast.toast('Wellness zaktualizowany', {
                        title: `Wellness`,
                        variant: 'success',
                        solid: true
                    })
                }).catch(err => {
                    this.$bvToast.toast(err, {
                        title: `Błąd`,
                        variant: 'danger',
                        solid: true
                    })
                })
            }
        }
    }
</script>
