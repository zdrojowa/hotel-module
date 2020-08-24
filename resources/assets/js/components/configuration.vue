<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <b-button type="bytton" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Nazwa</label>
                    <input type="text" :class="getInputClass('name')" name="name" placeholder="Wpisz nazwe" v-model.lazy="name">
                    <small v-if="hasError('name')" class="error mt-2 text-danger">{{ errors.name[0] }}</small>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Sala</label>
                    <multiselect v-model.lazy="hall" :options="halls" track-by="_id" label="name" placeholder="Wybierz sale"></multiselect>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-12">
                <div class="form-group">
                    <b-form-group label="Obrazek">
                        <media-selector extensions="jpg,jpeg,png" @media-selected="select"></media-selector>
                    </b-form-group>

                    <b-img v-if="image" thumbnail fluid :src="image"></b-img>

                    <b-button v-if="image" type="button" variant="danger" @click="remove">Usuń</b-button>
                </div>
            </div>

        </div>

        <div class="row mt-3">
            <b-input-group prepend-html="Przestrzeń(m<sup>2</sup>)" class="col-md-6">
                <b-form-input v-model.lazy="square" type="number"></b-form-input>
            </b-input-group>
            <b-input-group prepend="Liczba osób" class="col-md-6">
                <b-form-input v-model.lazy="guests_count" type="number"></b-form-input>
            </b-input-group>
        </div>

        <div v-for="lang in langs" class="row mt-3">
            <div class="col-md-6">
                <label>Tytuł ({{ lang.name }})</label>
                <b-form-input v-model.lazy="titles[lang.key]"></b-form-input>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <b-form-group :label="'Plan (' + lang.name + ')'">
                        <div v-if="plans[lang.key]">
                            <a :href="plans[lang.key]" target="_blank">
                                <b-icon-file-text></b-icon-file-text>
                            </a>
                            <b-button type="button" variant="danger" @click="removePlan(lang.key)">Usuń</b-button>
                        </div>
                        <media-selector v-else extensions="pdf" @media-selected="selectPlan(lang.key, $event)"></media-selector>
                    </b-form-group>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: 'configuration',
        props: {
            _id: {
                required: true,
                type: String
            }
        },

        data() {
            return {
                langs: [],
                halls: [],
                id: 0,
                name: '',
                hall: {},
                image: '',
                titles: {},
                plans: {},
                square: 0,
                guests_count: 0,
                errors: {
                    name: {}
                }
            };
        },

        created() {
            this.getLangs();
            this.getHalls();
        },

        computed: {

            url() {
                return this.id
                    ? ('/dashboard/hotels-conference-configuration/' + this.id)
                    : '/dashboard/hotels-conference-configuration/store';
            }
        },

        methods: {

            select: function(url) {
                this.image = url;
            },

            remove: function() {
                this.image = '';
            },

            selectPlan: function(lang, $event) {
                this.plans[lang] = $event;
            },

            removePlan: function(lang) {
                this.plans[lang] = '';
            },

            getLangs: function() {
                axios.get('/dashboard/languages/get')
                    .then(res => {
                        this.langs = res.data;
                        this.getConfiguration();
                    }).catch(err => {
                    console.log(err)
                })
            },

            getHalls: function() {
                let self = this;

                axios.get('/api/hotels/conference/hall')
                .then(res => {
                    self.halls = res.data;
                }).catch(err => {
                    console.log(err)
                })
            },

            hasError: function(key) {
                return this.errors[key].length > 0;
            },

            getInputClass: function(key) {
                let className = 'form-control ';
                if (this.hasError(key)) {
                    className += 'is-invalid';
                } else {
                    if (this[key]) {
                        className += 'is-valid';
                    }
                }
                return className;
            },

            getItem: function(arr, key, val) {

                let item = val;

                arr.forEach(it => {
                    if (it[key] === val) {
                        item = it;
                    }
                });

                return item;
            },

            getConfiguration: function() {
                let self = this;
                if (self._id) {
                    axios.get('/api/hotels/conference/configuration?id=' + self._id)
                    .then(res => {
                        self.id           = res.data._id;
                        self.name         = res.data.name;
                        self.image        = res.data.image;
                        self.square       = res.data.square;
                        self.guests_count = res.data.guests_count;

                        self.hall = self.getItem(self.halls, '_id', res.data.hall);

                        if (res.data.titles != null) {
                            self.titles = res.data.titles;
                            self.checkLangs(self.titles);
                        }

                        if (res.data.plans != null) {
                            self.plans = res.data.plans;
                            self.checkLangs(self.plans);
                        }

                    }).catch(err => {
                        console.log(err)
                    })
                }
                self.checkLangs(self.titles);
                self.checkLangs(self.plans);
            },

            checkLangs: function(field) {
                let self = this;
                self.langs.forEach(lang => {
                    if (!(lang.key in field)) {
                        field[lang.key] = '';
                    }
                });
            },

            validate: function(e) {
                if (this.name) {
                    this.errors.name = {};
                    return true;
                } else {
                    this.errors.name = ['To pole jest wymagane'];
                }
                return false;
            },

            save: function() {
                let self = this;

                if (self.validate) {

                    let formData = new FormData();
                    formData.append('_method', self.id ? 'PUT' : 'POST');
                    formData.append('name', self.name);
                    formData.append('hall', self.hall._id);
                    formData.append('image', self.image);
                    formData.append('square', self.square);
                    formData.append('guests_count', self.guests_count);
                    formData.append('titles', JSON.stringify(self.titles));
                    formData.append('plans', JSON.stringify(self.plans));

                    axios.post(self.url, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(res => {
                        window.location = res.data.redirect;
                    }).catch(err => {
                        console.log(err);
                    });
                }
            }
        },

        watch: {
            name() {
                this.validate();
            }
        }
    }
</script>
