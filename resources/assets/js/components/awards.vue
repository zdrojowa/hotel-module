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
            <draggable class="list-group" ghost-class="ghost" :list="awards">
                <div class="list-group-item" v-for="(element, index) in awards" :key="element">
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
                        <label>Nagrody</label>
                        <multiselect :options="options" track-by="id" label="name" placeholder="Wybierz nagrodę" v-model.lazy="award"></multiselect>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>

    export default {
        name: 'awards',
        props : ['_id'],

        data() {
            return {
                awards: [],
                options: [],
                award: {}
            };
        },

        created() {
            this.getAwards();
        },

        methods: {

            getAwards: function() {
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
                    axios.get('/api/hotels/kitchen?id=' + self._id)
                    .then(res => {
                        if (res.data.awards != null) {
                            self.awards = res.data.awards;
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
                this.awards.push(this.award.id);
            },

            remove: function(position) {
                this.awards.splice(position, 1);
            },

            save: function(e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append('_method','PUT');
                formData.append('awards', JSON.stringify(this.awards));

                axios.post('/dashboard/hotels-kitchen/' + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(res => {
                        this.$bvToast.toast('Nagrody zaktualizowane', {
                            title: `Nagrody`,
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
