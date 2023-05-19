<template>
    <button class="btn btn-primary text-uppercase font-weight-bold" @click="addToCartLocal(product)">add to cart</button>
</template>
<script>
import { addToCart } from './services/AddToCartService';

export default {
    props: {
        product: Object,
    },
    methods: {
        async addToCartLocal(product) {
            const response = await addToCart(product.id);
            if (response) {
                this.$toast.success(product.name + " added to cart");
            } else {
                emitter.emit('requestErrorPopup');
            }
        },
    },
}
</script>
