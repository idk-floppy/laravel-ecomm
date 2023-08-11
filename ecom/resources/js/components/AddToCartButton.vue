<template>
  <ButtonBaseComponent
    class="btn-primary font-weight-bold"
    @click="addToCartLocal(product_id)"
  >
    <template #icon><i class="bi bi-cart-fill pe-2"></i></template>
    <template #text>add to cart</template>
  </ButtonBaseComponent>
</template>
<script>
import { addToCart } from "./services/AddToCartService";
import ButtonBaseComponent from "./ButtonBaseComponent.vue";

export default {
    props: {
        product_id: Number,
        product_name: {
            type: String,
            default: "Product"
        },
    },
    components: {
        ButtonBaseComponent,
    },
    methods: {
        async addToCartLocal(product_id) {
            const response = await addToCart(product_id);
            this.$store.dispatch('updateCartItems', response.data.cart);
            if (response.data.success) {
                emitter.emit("flashToast", { icon: 'success', title: `${this.product_name} added to cart` });
            } else {
                emitter.emit("requestErrorPopup");
            }
        },
    },
};
</script>
