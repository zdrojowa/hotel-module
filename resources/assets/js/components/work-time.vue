<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <b-button type="bytton" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>
        <div v-for="lang in langs" class="row mt-3">
            <b-input-group :prepend="'Dni otwarcia (' + lang.name + ')'" class="col-md-12">
                <b-form-input v-model.lazy="work_days[lang.key]"></b-form-input>
            </b-input-group>
        </div>
        <div class="row mt-3">
            <b-input-group prepend="Godziny otwarcia" class="col-sm-12">
                <b-form-input v-model.lazy="work_hours"></b-form-input>
            </b-input-group>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'work-time',
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
            }
        },

        data() {
            return {
                langs: [],
                work_days: {},
                work_hours: ''
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
                    axios.get(self.url_get + '?id=' + self._id)
                        .then(res => {
                            self.work_hours = res.data.work_hours;
                            if (res.data.work_days != null) {
                                self.work_days = res.data.work_days;
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
                    if (!(lang.key in self.work_days)) {
                        self.work_days[lang.key] = '';
                    }
                });
            },

            save: function(e) {
                let self = this;
                e.preventDefault();

                let formData = new FormData();
                formData.append('_method','PUT');
                formData.append('work_days', JSON.stringify(this.work_days));
                formData.append('work_hours', this.work_hours);

                axios.post(self.url_post + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(res => {
                        this.$bvToast.toast('Termin pracy zaktualizowany', {
                            title: `Termin pracy`,
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
