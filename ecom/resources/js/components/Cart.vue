// Cart.vue
<template>
  <div>
    <div v-if="cartItemsCount > 0">
      <ul>
        <li v-for="(item, index) in cartItems" :key="index">
          {{ item.name }} - {{ item.price }} Ft
          <button @click="removeItem(index)">Remove</button>
        </li>
      </ul>
      <div>Total price: {{ cartTotalPrice }} Ft</div>
      <button @click="clearCart">Clear cart</button>
    </div>
    <div v-else>
      Cart is empty
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      cartItems: JSON.parse(localStorage.getItem('cart')) || [],
    };
  },
  created() {
    this.emitter.on('buy', this.buy);
    window.addEventListener('beforeunload', this.saveCartToLocalStorage);
  },
  beforeUnmount() {
    this.emitter.off('buy', this.buy);
    window.removeEventListener('beforeunload', this.saveCartToLocalStorage);
    this.saveCartToLocalStorage();
  },
  methods: {
    buy(item) {
      this.cartItems.push(item);
    },
    removeItem(index) {
      this.cartItems.splice(index, 1);
    },
    clearCart() {
      this.cartItems = [];
    },
    saveCartToLocalStorage() {
      localStorage.setItem('cart', JSON.stringify(this.cartItems));
    },
  },
  computed: {
    cartItemsCount() {
      return this.cartItems.length;
    },
    cartTotalPrice() {
      return this.cartItems.reduce((total, item) => total + item.price, 0);
    },
  },
};
</script>
