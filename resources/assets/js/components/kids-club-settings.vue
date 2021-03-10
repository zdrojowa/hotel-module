<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <b-button type="bytton" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>

        <b-card>
            <b-navbar>
                <b-navbar-nav>
                    <h4>Plan zajęć (ikonki)</h4>
                </b-navbar-nav>
                <b-navbar-nav class="ml-auto">
                    <b-nav-form>
                        <b-button type="button" variant="success" v-b-modal.modal>Dodaj</b-button>
                    </b-nav-form>
                </b-navbar-nav>
            </b-navbar>

            <div class="row item-container">
                <draggable class="list-group" ghost-class="ghost" :list="kids_club_icons" handle=".handle">
                    <div class="list-group-item" v-for="(element, index) in kids_club_icons" :key="index + element.id">
                        <div class="item gallery-item">
                            <div>
                                <button type="button" aria-label="Close" class="close" @click="remove(index)">×</button>
                            </div>
                            <b-icon-arrows-move class="handle"></b-icon-arrows-move>
                            <span>{{ getName(element.id) }}</span>
                            <div class="gallery-form px-3">
                                <div v-for="lang in langs" class="row mt-3">
                                    <b-input-group :prepend="'Tytuł (' + lang.name + ')'" class="col-md-6">
                                        <b-form-input v-model.lazy="element.titles[lang.key]"></b-form-input>
                                    </b-input-group>
                                    <b-input-group :prepend="'Opis (' + lang.name + ')'" class="col-md-6">
                                        <b-form-input v-model.lazy="element.descriptions[lang.key]"></b-form-input>
                                    </b-input-group>
                                </div>
                            </div>
                        </div>
                    </div>
                </draggable>
            </div>
        </b-card>

        <b-modal id="modal" title="Dodawanie" hide-footer>
            <b-nav align="right">
                <b-nav-item>
                    <b-button type="button" variant="success" @click="add">Zapisz</b-button>
                </b-nav-item>
            </b-nav>
            <div class="row">

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Ikonki</label>
                        <multiselect :options="options" track-by="id" label="name" placeholder="Wybierz ikonkę" v-model.lazy="icon"></multiselect>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>
export default {
    name: 'conference',
    props: {
        id: {
            required: true,
            type: String
        }
    },

    data() {
        return {
            options: [],
            icon: {},
            langs: [],
            conference_link: {},
            kids_club_icons: []
        };
    },

    created() {
        this.getLangs()
        this.getIcons()
    },

    methods: {

        getIcons: function() {
            let self = this

            axios.get('/api/icons')
                .then(res => {
                    self.options = res.data
                }).catch(err => {
                console.log(err)
            })
        },

        getName: function(id) {
            let name = ''
            this.options.forEach(item => {
                if (item.id === id) {
                    name = item.name
                }
            });
            return name
        },

        getLangs: function() {
            axios.get('/dashboard/languages/get')
                .then(res => {
                    this.langs = res.data
                    this.getItem()
                }).catch(err => {
                console.log(err)
            })
        },

        getItem() {
            let self = this;
            axios.get('/api/hotels?id=' + self.id)
                .then(res => {
                    if (res.data.kids_club_icons != null) {
                        self.kids_club_icons = res.data.kids_club_icons
                        self.kids_club_icons.forEach(icon => {
                            self.checkLangs(icon.titles)
                            self.checkLangs(icon.descriptions)
                        })
                    }
                }).catch(err => {
                console.log(err)
            })
        },

        checkLangs: function(field) {
            let self = this
            self.langs.forEach(lang => {
                if (!(lang.key in field)) {
                    field[lang.key] = ''
                }
            })
        },

        remove(index) {
            this.kids_club_icons.splice(index, 1)
        },

        add() {
            this.kids_club_icons.push({id: this.icon.id, titles: {}, descriptions: {}})
        },

        save: function() {
            let self = this

            let formData = new FormData()
            formData.append('_method', 'PUT')
            formData.append('conference_link', JSON.stringify(self.conference_link))
            formData.append('kids_club_icons', JSON.stringify(self.kids_club_icons))

            axios.post('/dashboard/hotels/' + self.id, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(res => {
                this.$bvToast.toast('Ikonki zaktualizowane', {
                    title: `Ikonki`,
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
