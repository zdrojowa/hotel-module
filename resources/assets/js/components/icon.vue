<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <b-button type="button" variant="success" v-b-modal.modal>Dodaj</b-button>
            </b-nav-item>
            <b-nav-item>
                <b-button type="button" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>

        <div class="row item-container">
            <draggable class="list-group" ghost-class="ghost" :list="icons">
                <div class="list-group-item" v-for="(element, index) in icons" :key="element">
                    <div class="item file-item">
                        <span>{{ getName(element) }}</span>
                        <button type="button" aria-label="Close" class="close" @click="remove(index)">×</button>
                    </div>
                </div>
            </draggable>
        </div>

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
        name: 'icon',
        props : ['_id', 'url_get', 'url_post', 'field'],

        data() {
            return {
                icons: [],
                options: [],
                icon: {}
            };
        },

        created() {
            this.getIcons();
        },

        methods: {

            getIcons: function() {
                let self = this;

                axios.get('/api/icons')
                    .then(res => {
                        self.options = res.data;
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
                            self.icons = res.data[self.field];
                        }
                    }).catch(err => {
                        console.log(err);
                    })
                }
            },

            getName: function(id) {
                let name = '';
                this.options.forEach(item => {
                    if (item.id === id) {
                        name = item.name;
                    }
                });
                return name;
            },

            add: function() {
                this.icons.push(this.icon.id);
            },

            remove: function(position) {
                this.icons.splice(position, 1);
            },

            save: function(e) {
                let self = this;
                e.preventDefault();

                let formData = new FormData();
                formData.append('_method','PUT');
                formData.append(self.field, JSON.stringify(self.icons));

                axios.post(self.url_post + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(res => {
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
