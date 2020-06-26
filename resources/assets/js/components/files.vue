<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <b-button type="button" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>

        <div v-for="lang in langs" class="row">
            <div class="row">
                <b-form-group :label="lang.name">
                    <media-selector extensions="all" @media-selected="select(lang.key, $event)"></media-selector>
                </b-form-group>
            </div>

            <div class="row item-container">
                <draggable class="list-group" ghost-class="ghost" :list="files[lang.key]">
                    <div class="list-group-item" v-for="(element, index) in files[lang.key]" :key="lang.key + element.url">
                        <div class="item file-item">
                            <a :href="element.url" target="_blank">
                                <i class="mdi mdi-file-outline"></i>
                            </a>
                            <b-input-group prepend="Opis" class="mx-2">
                                <b-form-input v-model.lazy="element.name"></b-form-input>
                            </b-input-group>
                            <button type="button" aria-label="Close" class="close" @click="remove(lang.key, index)">×</button>
                        </div>
                    </div>
                </draggable>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'files',
        props : ['_id'],

        data() {
            return {
                langs: [],
                files: {}
            };
        },

        created() {
            this.getLangs();
        },

        methods: {

            select: function(lang, $event) {
                this.files[lang].push({url: $event, name: ''});
            },

            remove: function(lang, position) {
                this.files[lang].splice(position, 1);
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
                    axios.get('/api/hotels/wellness?id=' + self._id)
                        .then(res => {
                            if (res.data.files != null) {
                                self.files = res.data.files;
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
                    if (!(lang.key in self.files)) {
                        self.files[lang.key] = [];
                    }
                });
            },

            save: function(e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append('_method','PUT');
                formData.append('files', JSON.stringify(this.files));

                axios.post('/dashboard/hotels-wellness/' + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(res => {
                        this.$bvToast.toast('Pliki zaktualizowane', {
                            title: `Pliki`,
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
