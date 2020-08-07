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
                <label for="timepicker-placeholder">Check-in</label>
                <b-form-timepicker id="timepicker-placeholder" placeholder="Wybierz check-in" local="pl" v-model.lazy="check_in"></b-form-timepicker>
            </div>

            <div class="col-md-6">
                <label for="timepicker-placeholder">Check-out</label>
                <b-form-timepicker id="timepicker-placeholder" placeholder="Wybierz check-out" local="pl" v-model.lazy="check_out"></b-form-timepicker>
            </div>
        </div>

        <div class="row mt-2">

            <div class="col-md-6">
                <b-form-checkbox v-model.lazy="animals" switch>
                    Zwierzeta
                </b-form-checkbox>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'info',
        props : ['_id'],

        data() {
            return {
                check_in: '',
                check_out: '',
                animals: false
            };
        },

        created() {
            this.getHotel();
        },

        methods: {

            getHotel: function() {
                let self = this;

                axios.get('/api/hotels?id=' + self._id)
                .then(res => {
                    self.check_in  = res.data.check_in;
                    self.check_out = res.data.check_out;
                    self.animals   = res.data.animals;
                }).catch(err => {
                    console.log(err)
                })
            },

            save: function(e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('check_in', this.check_in);
                formData.append('check_out', this.check_out);
                formData.append('animals', this.animals);

                axios.post('/dashboard/hotels/' + this._id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    this.$bvToast.toast('Informacja zaktualizowana', {
                        title: `Informacja`,
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
