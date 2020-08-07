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
                <ckeditor :editor="editor" v-model="parking[lang.key]" :config="editorConfig"></ckeditor>
            </div>
        </div>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        name: 'parking',
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
                parking: {}
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
                        if (res.data.parking != null) {
                            self.parking = res.data.parking;
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
                    if (!(lang.key in self.parking)) {
                        self.parking[lang.key] = '';
                    }
                });
            },

            save: function(e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append('_method','PUT');
                formData.append('parking', JSON.stringify(this.parking));

                axios.post('/dashboard/hotels/' + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(res => {
                        this.$bvToast.toast('Informacja o parkingu zaktualizowana', {
                            title: `Parking`,
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
