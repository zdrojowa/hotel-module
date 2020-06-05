<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <b-button type="button" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>

        <nested v-model="list"/>
    </div>
</template>

<script>

    export default {
        name: 'list',
        props: {},

        data() {
            return {
                list: []
            };
        },

        created() {
            this.getList();
        },

        methods: {

            getList() {
                let self = this;
                axios.get('/api/hotels')
                    .then(res => {
                        if (typeof res.data == 'undefined') {
                            self.list = [];
                        } else {
                            self.list = res.data;
                        }
                    }).catch(err => {
                    console.log(err)
                })
            },

            save: function() {
                let formData = new FormData();
                formData.append('_method', 'POST');
                formData.append('list', JSON.stringify(this.list));

                axios.post('/dashboard/hotels/saveOrder', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(res => {
                    window.location = res.data.redirect;
                }).catch(err => {
                    console.log(err);
                });
            }
        }
    }
</script>
