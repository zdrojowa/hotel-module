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
                <ckeditor :editor="editor" v-model="programs[lang.key]" :config="editorConfig"></ckeditor>
            </div>
        </div>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        name: 'program',
        props : ['_id'],

        data() {
            return {
                editor: ClassicEditor,
                editorConfig: {
                    toolbar: {
                        items: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo']
                    }
                },
                langs: [],
                programs: {}
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
                        this.getOffer();
                    }).catch(err => {
                    console.log(err)
                })
            },

            getOffer: function() {
                let self = this;
                if (self._id) {
                    axios.get('/api/offers?id=' + self._id)
                        .then(res => {
                            if (res.data.programs != null) {
                                self.programs = res.data.programs;
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
                    if (!(lang.key in self.programs)) {
                        self.programs[lang.key] = '';
                    }
                });
            },

            save: function(e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append('_method','PUT');
                formData.append('programs', JSON.stringify(this.programs));

                axios.post('/dashboard/offers/' + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(res => {
                        this.$bvToast.toast('Programy zaktualizowane', {
                            title: `Programy`,
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
