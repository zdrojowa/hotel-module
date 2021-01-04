<template>
    <div>
        <slot></slot>

        <b-nav align="right">
            <b-nav-item>
                <b-button type="button" variant="primary" @click="save">Zapisz</b-button>
            </b-nav-item>
        </b-nav>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>ID</label>
                    <input type="text" :class="getInputClass('id')" name="id" placeholder="Wpisz id" v-model.lazy="id">
                    <small v-if="hasError('id')" class="error mt-2 text-danger">{{ errors.id[0] }}</small>
                </div>
            </div>
	        <div class="col-md-6">
		        <div class="form-group">
			        <label>Access key</label>
			        <input type="text" :class="getInputClass('key')" name="key" placeholder="Wpisz access key" v-model.lazy="key">
			        <small v-if="hasError('key')" class="error mt-2 text-danger">{{ errors.key[0] }}</small>
		        </div>
	        </div>

        </div>

	    <div class="row">
		    <div class="col-md-12">
			    <div class="form-group">
				    <label>Link</label>
				    <input type="text" :class="getInputClass('link')" name="link" placeholder="Link" v-model.lazy="link">
				    <small v-if="hasError('link')" class="error mt-2 text-danger">{{ errors.link[0] }}</small>
			    </div>
		    </div>
	    </div>
    </div>
</template>

<script>

    export default {
        name: 'customer-alliance',
        props : {
            _id: {
                type: String,
                default: ''
            }
        },

        data() {
            return {
                id: '',
                key: '',
                link: '',
                errors: {
                    id: {},
                    key: {},
                    link: {},
                }
            };
        },

        created() {
            this.getHotel()
        },

        methods: {

            hasError: function(key) {
                return this.errors[key].length > 0
            },

            getInputClass: function(key) {
                let className = 'form-control '
                if (this.hasError(key)) {
                    className += 'is-invalid'
                } else {
                    if (this[key]) {
                        className += 'is-valid'
                    }
                }
                return className
            },

            getHotel: function() {
                let self = this;

                axios.get('/api/hotels?id=' + this._id)
                .then(res => {
                    self.id = res.data.ca_id
                    self.key = res.data.ca_key
                    self.link = res.data.ca_link
                }).catch(err => {
                    console.log(err)
                })
            },

            validate: function(e) {
                if (this.id) {
                    this.errors.id = {}
                    return true;
                } else {
                    this.errors.id = ['To pole jest wymagane']
                }
                return false;
            },

            save: function(e) {
                e.preventDefault()

                if (this.validate) {
                    let formData = new FormData();
                    formData.append('_method', 'PUT')
                    formData.append('ca_id', this.id)
                    formData.append('ca_key', this.key)
                    formData.append('ca_link', this.link)

                    axios.post('/dashboard/hotels/' + this._id, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(res => {
	                    this.$bvToast.toast('Customer alliance zaktualizowane', {
		                    title: `Customer alliance`,
		                    variant: 'success',
		                    solid: true
	                    })
                    }).catch(err => {
	                    this.$bvToast.toast(err, {
		                    title: `Błąd`,
		                    variant: 'danger',
		                    solid: true
	                    })
                    })
                } else {
                    return false
                }
            }
        },

        watch: {
            id() {
                this.validate()
            }
        }
    }
</script>
