<template>
    <div class="spinner-container" v-if="isLoading">
      <div class="spinner-border" role="status"></div>
    </div>
</template>
<script>
export default {
    computed: {
        isLoading() {
            return this.$store.getters.getIsLoading;
        }
    }
}
</script>
<style scoped>
.spinner-container {
    position: fixed;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999 !important;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.25);
}
</style>
