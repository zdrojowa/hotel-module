<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <b-button type="bytton" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>

        <div v-for="lang in langs" class="row">
            <b-input-group :prepend="'Tytuł (' + lang.name + ')'" class="mt-3">
                <b-form-input v-model.lazy="gallery.titles[lang.key]"></b-form-input>
            </b-input-group>
        </div>

        <div v-for="lang in langs" class="row mt-3">
            <div class="form-group col-sm-12">
                <label>Opis ({{ lang.name }})</label>
                <ckeditor :editor="editor" v-model="gallery.descriptions[lang.key]" :config="editorConfig"></ckeditor>
            </div>
        </div>

        <div class="row">
            <media-selector extensions="jpg,jpeg,png" @media-selected="add"></media-selector>
        </div>

        <div class="row item-conteiner">
            <draggable class="list-group" ghost-class="ghost" :list="gallery.images">
                <div class="list-group-item" v-for="(element, index) in gallery.images" :key="element.url">
                    <div class="item gallery-item">
                        <div>
                            <button type="button" aria-label="Close" class="close" @click="remove(index)">×</button>
                        </div>
                        <div class="thumbnail-img">
                            <img :src="element.url" class="img-thumbnail">
                        </div>
                        <div class="gallery-form px-3">
                            <div v-for="lang in langs" class="row mt-3">
                                <b-input-group :prepend="'Tytuł (' + lang.name + ')'" class="col-md-6">
                                    <b-form-input v-model.lazy="element.titles[lang.key]"></b-form-input>
                                </b-input-group>
                                <b-input-group :prepend="'Opis (' + lang.name + ')'" class="col-md-6">
                                    <b-form-textarea v-model.lazy="element.descriptions[lang.key]"></b-form-textarea>
                                </b-input-group>
                            </div>
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
        name: 'spa-gallery',
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
                element: {url: '', titles: {}, descriptions: {}},
                gallery: {titles: {}, descriptions: {}, images:[]}
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
                        this.getGallery();
                    }).catch(err => {
                    console.log(err)
                })
            },

            getGallery() {
                let self = this;
                axios.get('/api/hotels/spa?id=' + self.id)
                    .then(res => {
                        if (res.data.gallery != null) {
                            self.gallery = res.data.gallery;
                            self.checkLangs(self.gallery.titles);
                            self.checkLangs(self.gallery.descriptions);
                            self.gallery.images.forEach(image => {
                                self.checkLangs(image.titles);
                                self.checkLangs(image.descriptions);
                            });
                        }
                    }).catch(err => {
                        console.log(err)
                })
                self.checkLangs(self.gallery.titles);
                self.checkLangs(self.gallery.descriptions);
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
                this.gallery.images.splice(index, 1);
            },

            add(url) {
                this.gallery.images.push({url: url, titles: {}, descriptions: {}});
            },

            save: function() {
                let self = this;

                let formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('gallery', JSON.stringify(self.gallery));

                axios.post(this.url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(res => {
                    this.$bvToast.toast('Galeria zaktualizowana', {
                        title: `Galeria`,
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
