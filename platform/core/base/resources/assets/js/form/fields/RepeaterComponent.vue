<template>
    <div class="repeater-group">
        <div class="form-group" v-for="(item, index) in items">
            <div v-html="item"></div>
            <span class="remove-item-button" type="button" @click="deleteRow(index)"><i class="fa fa-times"></i></span>
        </div>

        <button class="btn btn-info" type="button" @click="addRow">{{ __('Add new') }}</button>
    </div>
</template>

<style>

.repeater-item-group {
    padding       : 10px;
    border        : 1px solid #aaaaaa;
    padding-right : 45px;
}

.repeater-group {
    padding    : 10px;
    background : rgba(0, 0, 0, 0.05);
}

.repeater-group .remove-item-button {
    position      : absolute;
    right         : 10px;
    top           : 10px;
    width         : 25px;
    height        : 25px;
    display       : inline-block;
    border-radius : 50%;
    background    : #c3c3c3;
    line-height   : 25px;
    text-align    : center;
}

</style>

<script>

export default {
    data: function () {
        return {
            items: []
        };
    },
    props: {
        fields: {
            type: Array,
            default: () => [],
            required: true
        },
        added: {
            type: Array,
            default: () => [],
            required: true
        }
    },

    mounted() {
        if (!this.added.length) {
            this.addRow();
        } else {
            for (const item of this.added) {
                this.items.push(item);
            }
        }
    },

    methods: {
        addRow: function () {
            for (const item of this.fields) {
                this.items.push(item.replaceAll('__key__', this.items.length));
            }
        },
        deleteRow: function (index) {
            this.items.splice(index, 1);
        },
        removeSelectedItem: function () {
            for(const item of this.items) {
                this.items.slice(i, 1);
            }
        }
    }
}
</script>
