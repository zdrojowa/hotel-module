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
            <draggable class="list-group" ghost-class="ghost" :list="conveniences">
                <div class="list-group-item" v-for="(element, index) in conveniences" :key="element">
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
                        <label>Udogodnienia</label>
                        <multiselect :options="options" track-by="id" label="name" placeholder="Wybierz udogodnienie" v-model.lazy="convenience"></multiselect>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>

    export default {
        name: 'convenience',
        props : ['_id'],

        data() {
            return {
                conveniences: [],
                options: [],
                convenience: {}
            };
        },

        created() {
            this.getConveniences();
        },

        methods: {

            getConveniences: function() {
                let self = this;

                axios.get('/api/icons')
                    .then(res => {
                        self.options = res.data;
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
                            if (res.data.conveniences != null) {
                                self.conveniences = res.data.conveniences;
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
                this.conveniences.push(this.convenience.id);
            },

            remove: function(position) {
                this.conveniences.splice(position, 1);
            },

            save: function(e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append('_method','PUT');
                formData.append('conveniences', JSON.stringify(this.conveniences));

                axios.post('/dashboard/offers/' + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(res => {
                        this.$bvToast.toast('Udogodnienia zaktualizowane', {
                            title: `Udogodnienia`,
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
