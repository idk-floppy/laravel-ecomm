<template>
    <div>
        <button class="btn" data-bs-toggle="modal" data-bs-target="#cartModal" @click="this.logInfo">MiniCart ({{ cartItemCount }})</button>
    </div>
</template>
<script>
export default {
    computed: {
        cartItemCount() {
            return this.$store.getters.getCartItemCount;
        },
    },
    methods: {
        logInfo() {
            console.log(this.cartItemCount);
        }
    },
}
</script>
