<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <b-button type="button" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>

        <div v-for="lang in langs" class="row">
            <div class="form-group col-sm-12">
                <label>{{ lang.name }}</label>
                <ckeditor :editor="editor" v-model="descriptions[lang.key]" :config="editorConfig"></ckeditor>
            </div>
        </div>

        <div class="col-md-6">
            <b-form-checkbox v-model.lazy="animals_phone" switch>
                Pokaż numer telefonu
            </b-form-checkbox>
        </div>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        name: 'description',
        props : ['_id', 'url_get', 'url_post', 'field'],

        data() {
            return {
                editor: ClassicEditor,
                editorConfig: {
                    toolbar: {
                        items: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo']
                    }
                },
                langs: [],
                descriptions: {},
                animals_phone: false
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
                        if (res.data[self.field] != null) {
                            self.descriptions = res.data[self.field];
                            self.checkLangs();
                        }
                        self.animals_phone = res.data.animals_phone || false
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
                    if (!(lang.key in self.descriptions)) {
                        self.descriptions[lang.key] = '';
                    }
                });
            },

            save: function(e) {
                let self = this;
                e.preventDefault();

                let formData = new FormData();
                formData.append('_method','PUT');
                formData.append(self.field, JSON.stringify(this.descriptions));
                formData.append('animals_phone', this.animals_phone);

                axios.post(self.url_post + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(res => {
                        this.$bvToast.toast('Opisy zaktualizowane', {
                            title: `Opisy`,
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
