<template>
  <div>
    <slot
      :cartItems="cartItems"
      :removeFromCart="removeFromCart"
      :productName="productName"
      :showQuantityInput="showQuantityInput"
      :hideQuantityInput="hideQuantityInput"
      :cartTotal="cartTotal"
      :hasItems="hasItems"
    ></slot>
  </div>
</template>

<script>
import { addToCart } from "./services/AddToCartService";

export default {
  data() {
    return {
      total: 0,
    };
  },
  methods: {
    async removeFromCart(cartitem_id) {
      this.$store.dispatch("modifyIsLoading", true);
      let response = await axios.post("cart/remove", {
        cartitem_id: cartitem_id,
      });
      if (response.data.success) {
        this.$store.dispatch("updateCartItems", response.data.cart);
        emitter.emit("flashToast", {
          icon: "success",
          title: `Cart successfully updated`,
        });
      }
      this.$store.dispatch("modifyIsLoading", false);
    },
    showQuantityInput(item, event) {
      item.showQuantityField = true;
      this.$nextTick(() => {
        event.currentTarget.querySelector("input").focus();
      });
    },
    async hideQuantityInput(item, event) {
      item.showQuantityField = false;
      let newQty = event.currentTarget.value;
      if (newQty != item.qty) {
        await this.updateQuantityByField(item, newQty);
      }
    },
    async updateQuantityByField(item, qty) {
      this.$store.dispatch("modifyIsLoading", true);
      let response = await addToCart(item.product.id, qty, "set");
      if (!response.data.success) {
        this.$store.dispatch("modifyIsLoading", false);
        emitter.emit("requestErrorPopup");
        return 0;
      }

      this.$store.dispatch("updateCartItems", response.data.cart);
      this.$store.dispatch("modifyIsLoading", false);
      emitter.emit("flashToast", {
        icon: "success",
        title: `${item.product.name} successfully updated`,
      });
    },
  },
  computed: {
    hasItems() {
      return this.$store.getters.getCartItemCount;
    },
    productName: function () {
      return function (cartItem) {
        return cartItem.product.name;
      };
    },
    cartItems() {
      return this.$store.getters.getCartItems;
    },
    cartTotal() {
      return this.$store.getters.getCartTotal;
    },
  },
};
</script>
