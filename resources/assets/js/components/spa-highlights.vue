<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <media-selector extensions="jpg,jpeg,png" @media-selected="add"></media-selector>
            </b-nav-item>
            <b-nav-item>
                <b-button type="bytton" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>

        <div class="row item-conteiner">
            <draggable class="list-group" ghost-class="ghost" :list="highlights">
                <div class="list-group-item" v-for="(element, index) in highlights" :key="element.url">
                    <div class="item gallery-item">
                        <div>
                            <button type="button" aria-label="Close" class="close" @click="remove(index)">×</button>
                        </div>
                        <div class="thumbnail-img">
                            <img :src="element.url" class="img-thumbnail">
                        </div>
                        <div class="gallery-form px-3">
                            <div v-for="lang in langs" class="row mt-3">
                                <b-input-group :prepend="'Tytuł (' + lang.name + ')'" class="col-md-12">
                                    <b-form-input v-model.lazy="element.titles[lang.key]"></b-form-input>
                                </b-input-group>
                                <b-input-group :prepend="'Opis (' + lang.name + ')'" class="col-md-12">
                                    <ckeditor :editor="editor" v-model="element.descriptions[lang.key]" :config="editorConfig"></ckeditor>
                                </b-input-group>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <b-input-group prepend="Czas" class="col-md-6">
                                <b-form-input v-model.lazy="element.time"></b-form-input>
                            </b-input-group>
                            <b-input-group prepend="Cena" class="col-md-6">
                                <b-form-input v-model.lazy="element.price"></b-form-input>
                            </b-input-group>
                        </div>
                        <div v-for="lang in langs" class="row mt-3">
                            <b-input-group :prepend="'Dni pracy (' + lang.name + ')'" class="col-md-12">
                                <b-form-input v-model.lazy="element.work_days[lang.key]"></b-form-input>
                            </b-input-group>
                        </div>
                        <div class="row mt-3">
                            <b-input-group prepend="Godziny pracy" class="col-md-4">
                                <b-form-input v-model.lazy="element.work_time"></b-form-input>
                            </b-input-group>
                            <b-input-group prepend="Waga" class="col-md-4">
                                <b-form-input v-model.lazy="element.weight"></b-form-input>
                            </b-input-group>
                            <b-input-group prepend="Wiek" class="col-md-4">
                                <b-form-input v-model.lazy="element.years"></b-form-input>
                            </b-input-group>
                        </div>
                    </div>
                </div>
            </draggable>
        </div>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        name: 'spa-highlights',
        props: {
            id: {
                required: true,
                type: String
            }
        },

        data() {
            return {
                editor: ClassicEditor,
                editorConfig: {
                    toolbar: {
                        items: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo']
                    }
                },
                langs: [],
                element: {url: '', titles: {}, descriptions: {}, time: '', price: 0, work_days: {}, work_time: '', weight: 0, years: 0},
                highlights: []
            };
        },

        created() {
            this.getLangs();
        },

        computed: {

            url: function () {
                return '/dashboard/hotels-spa/' + this.id;
            }
        },

        methods: {

            getLangs: function() {
                axios.get('/dashboard/languages/get')
                    .then(res => {
                        this.langs = res.data;
                        this.getHighlights();
                    }).catch(err => {
                    console.log(err)
                })
            },

            getHighlights() {
                let self = this;
                axios.get('/api/hotels/spa?id=' + self.id)
                    .then(res => {
                        if (res.data.highlights != null) {
                            self.highlights = res.data.highlights;
                            self.highlights.forEach(highlight => {
                                self.checkLangs(highlight.titles);
                                self.checkLangs(highlight.descriptions);
                                self.checkLangs(highlight.work_days);
                            });
                        }
                    }).catch(err => {
                        console.log(err)
                })
            },

            checkLangs: function(field) {
                let self = this;
                self.langs.forEach(lang => {
                    if (!(lang.key in field)) {
                        field[lang.key] = '';
                    }
                });
            },

            remove(index) {
                this.highlights.splice(index, 1);
            },

            add(url) {
                this.highlights.push({url: url, titles: {}, descriptions: {}, time: '', price: 0, work_days: {}, work_time: '', weight: 0, years: 0});
            },

            save: function() {
                let self = this;

                let formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('highlights', JSON.stringify(self.highlights));

                axios.post(this.url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(res => {
                    this.$bvToast.toast('Highlights zaktualizowana', {
                        title: `Highlights`,
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
