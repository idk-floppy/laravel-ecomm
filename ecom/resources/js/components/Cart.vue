<template>
    <div class="container">
      <ul>
        <!-- <li v-for="product in cartProducts" :key="product.id">
          {{ product.name }} ({{ product.quantity }})
        </li> -->
      </ul>
    </div>
  </template>
<script>
export default {
    data() {
        return {
            cartProducts: [],
            total: 0,
            title: "hello",
        };
    },
    methods: {
        addProductToCart(product) {
            console.log(product+" product received by products grid component");
        },
    },
    mounted() {
        // console.log(this.cartProducts)
    }
}
</script>
