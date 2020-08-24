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
            <draggable class="list-group" ghost-class="ghost" :list="images">
                <div class="list-group-item" v-for="(element, index) in images" :key="index">
                    <div class="item gallery-item">
                        <div>
                            <button type="button" aria-label="Close" class="close" @click="remove(index)">×</button>
                        </div>
                        <div class="thumbnail-img">
                            <img :src="element" class="img-thumbnail">
                        </div>
                    </div>
                </div>
            </draggable>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'gallery',
        props: ['_id', 'url_get', 'url_post', 'field'],

        data() {
            return {
                images: []
            };
        },

        created() {
            this.getGallery();
        },

        methods: {

            getGallery() {
                let self = this;
                axios.get(self.url_get + '?id=' + self._id)
                    .then(res => {
                        if (typeof res.data[self.field] == 'undefined') {
                            self.images = [];
                        } else {
                           self.images = res.data[self.field];
                        }
                    }).catch(err => {
                        console.log(err)
                })
            },

            remove(index) {
                this.images.splice(index, 1);
            },

            add(url) {
                this.images.push(url);
            },

            save: function() {
                let self = this;

                let formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append(self.field, JSON.stringify(self.images));

                axios.post(this.url_post + self._id, formData, {
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
