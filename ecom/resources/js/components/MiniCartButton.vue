<template>
    <div>
        <button class="btn" data-bs-toggle="collapse" data-bs-target="#minicart">Cart ({{ cartItemCount }})</button>
    </div>
</template>
<script>
export default {
    computed: {
        cartItemCount() {
            return this.$store.getters.getCartItemCount;
        },
    },
}
</script>
