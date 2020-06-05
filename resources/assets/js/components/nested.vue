<template>
    <div class="row item-conteiner">
        <draggable class="list-group" ghost-class="ghost" @input="emitter" :value="value">
            <div class="list-group-item" v-for="element in value" :key="element._id">
                <div class="item">
                    {{ element.name }}
                </div>
            </div>
        </draggable>
    </div>
</template>

<script>
    export default {
        name: "nested",
        props: {
            value: {
                required: false,
                type: Array,
                default: null
            }
        },

        data() {
            return {
            };
        },

        methods: {

            emitter(value) {
                this.$emit("input", value);
            }
        }
    };
</script>
