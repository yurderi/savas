<template>
    <div class="form-container">
        <form v-on="listeners">
            <slot></slot>
        </form>
    </div>
</template>

<script>
export default {
    computed: {
        listeners() {
            return {
                ...this.$listeners
            }
        }
    }
}
</script>