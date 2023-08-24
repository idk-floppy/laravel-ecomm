<template>
  <Cart
    v-slot="{
      cartItems,
      removeFromCart,
      productName,
      showQuantityInput,
      hideQuantityInput,
      formatPrice,
      cartTotal,
      hasItems,
    }"
  >
    <div v-if="hasItems" class="container">
      <div class="border-bottom">
        <div
          v-for="(cartItem, index) in cartItems"
          :key="index"
          class="row mb-2 p-2 rounded cart-item"
        >
          <div class="col-2 col-md-2">
            <button class="btn btn-danger" @click="removeFromCart(cartItem.id)">
              <i class="bi bi-trash"></i>
            </button>
          </div>
          <div class="col-4 col-md-4 text-break">
            <a>{{ productName(cartItem) }}</a>
          </div>
          <div
            class="col-3 col-md-3"
            @click.stop="
              cartItem.showQuantityField
                ? ''
                : showQuantityInput(cartItem, $event)
            "
          >
            <input
              type="number"
              class="form-control"
              :class="{ 'd-none': !cartItem.showQuantityField }"
              :value="cartItem.qty"
              ref="qtyInput"
              @blur="hideQuantityInput(cartItem, $event)"
              @keyup.enter="hideQuantityInput(cartItem, $event)"
              min="1"
            />
            <p
              class="text-quantity"
              :class="{ 'd-none': cartItem.showQuantityField }"
            >
              {{ cartItem.qty }}
            </p>
          </div>
          <div class="col-3 col-md-3 text-md-right">
            {{ formatPrice(cartItem.subtotal) }}
          </div>
        </div>
      </div>
      <p class="float-end">Total: {{ formatPrice(cartTotal) }}</p>
    </div>
    <div v-else>Cart is empty</div>
  </Cart>
</template>
<script>
import Cart from "./Cart.vue";

export default {
  components: {
    Cart,
  },
};
</script>
<style scoped>
#cart-table {
  max-height: max(400px, 50vh);
}
.cart-item {
  transition: box-shadow 0.2s ease-in-out;
}
.cart-item:hover {
  box-shadow: 0 0 2px black;
}
</style>
