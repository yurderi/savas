<template>
    <div class="breadcrumb-container">
        <ul>
            <li v-for="(item, key) in activeItems">
                <router-link :to="item.route">
                    {{ getLabel(item) }}
                </router-link>
                <span class="icon">
                    <fa icon="caret-right"></fa>
                </span>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'v-breadcrumb',
    props: [
        'items'
    ],
    computed: {
        activeItems() {
            return this.items.filter(item => item.active !== false)
        }
    },
    methods: {
        getLabel (item) {
            if (typeof item.label === 'string') {
                return item.label
            } else if (typeof item.label === 'function') {
                return item.label()
            } else {
                return '???'
            }
        }
    }
}
</script>
