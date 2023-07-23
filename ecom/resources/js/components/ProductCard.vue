<template>
  <CardBase>
    <template v-slot:image
      ><img :src="getProductImage(product.image)" class="card-img-top"
    /></template>
    <template v-slot:title
      ><h5 class="card-title">
        <span role="button" @click="showProduct(product.id)"
          >{{ product.name }}</span
        >
      </h5></template
    >
    <template v-slot:details>{{ productPrice }}</template>
    <template v-slot:buttons>
      <AddToCartButton :product="product" />
      <div v-if="authenticated" class="btn-group">
        <EditItemButton :product="product" />
        <DeleteItemButton :product="product" />
      </div>
    </template>
  </CardBase>
</template>
<script>
import AddToCartButton from "./AddToCartButton.vue";
import EditItemButton from "./EditItemButton.vue";
import DeleteItemButton from "./DeleteItemButton.vue";
import CardBase from "./CardBase.vue";

export default {
  components: {
    AddToCartButton,
    EditItemButton,
    DeleteItemButton,
    CardBase,
  },
  props: {
    product: Object,
  },
  inject: ["authenticated"],
  methods: {
    showProduct(id) {
      return (window.location.href = "/products/" + id);
    },
    getProductImage(image) {
      if (image) {
        return window.location.origin + "/storage/" + image;
      }
      return null;
    },
  },
  computed: {
    productPrice() {
      return (
        this.product.price.toLocaleString("hu-HU", { useGrouping: true }) +
        " Ft"
      );
    },
  },
};
</script>
<style scoped>
.card-img-top {
  object-fit: cover;
  object-position: center;
  height: clamp(300px, 350px, 450px);
}
</style>
