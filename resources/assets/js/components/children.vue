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
                <ckeditor :editor="editor" v-model="children[lang.key]" :config="editorConfig"></ckeditor>
            </div>
        </div>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        name: 'children',
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
                children: {}
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
                        this.getHotel();
                    }).catch(err => {
                    console.log(err)
                })
            },

            getHotel: function() {
                let self = this;
                if (self._id) {
                    axios.get('/api/hotels?id=' + self._id)
                    .then(res => {
                        if (res.data.children != null) {
                            self.children = res.data.children;
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
                    if (!(lang.key in self.children)) {
                        self.children[lang.key] = '';
                    }
                });
            },

            save: function(e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append('_method','PUT');
                formData.append('children', JSON.stringify(this.children));

                axios.post('/dashboard/hotels/' + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(res => {
                        this.$bvToast.toast('Informacja o dzieciach zaktualizowana', {
                            title: `Dzieci`,
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
