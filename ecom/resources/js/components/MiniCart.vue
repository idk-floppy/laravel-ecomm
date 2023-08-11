<template>
<div class="position-sticky z-2">
    <div class="container">
        <div class="row position-absolute float-start bg-white shadow p-2 collapse" id="minicart">
            <div class="col">
                <div class="row mb-2">
                    <div class="col">
                        <h2>Cart</h2>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <cart-table></cart-table>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <a href="/cart" class="btn btn-primary w-100">Open Cart</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
export default {

}
</script>
<style scoped>
#minicart {
    width: clamp(25%, 450px, 100%);
}

.position-sticky {
    top: 55px;
}
</style>
