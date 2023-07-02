<template>
  <ButtonBaseComponent
    class="btn-primary font-weight-bold"
    @click="addToCartLocal(product)"
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
    product: Object,
  },
  components: {
    ButtonBaseComponent,
  },
  methods: {
    async addToCartLocal(product) {
      const response = await addToCart(product.id);
      if (response) {
        this.$toast.success(product.name + " added to cart");
      } else {
        emitter.emit("requestErrorPopup");
      }
    },
  },
};
</script>
