<template>
  <ButtonBaseComponent :disabled="loading"
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
            default: "Product",
        },
    },
    data() {
        return {
            loading: false,
        }
    },
    components: {
        ButtonBaseComponent,
    },
    methods: {
        async addToCartLocal(product_id) {
            this.$store.dispatch('modifyIsLoading', true);
            try {
                const response = await addToCart(product_id);
                if (response.data.success) {
                    this.$store.dispatch("updateCartItems", response.data.cart);
                    this.$store.dispatch('modifyIsLoading', false);
                    emitter.emit("flashToast", {
                        icon: "success",
                        title: `${this.product_name} added to cart`,
                    });
                } else {
                    emitter.emit("requestErrorPopup", { message: response.data.message });
                    this.$store.dispatch('modifyIsLoading', false);
                }
            } catch (error) {
                this.$store.dispatch('modifyIsLoading', false);
                emitter.emit("requestErrorPopup", {
                    message: error.response.data.message,
                });
            }
        },
    },
};
</script>
