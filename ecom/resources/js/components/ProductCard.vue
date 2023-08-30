<template>
  <CardBase>
    <template v-slot:image
      ><img :src="this.image" class="card-img-top"
    /></template>
    <template v-slot:title
      ><h5 class="card-title">
        <span role="button" @click="showProduct(id)">{{ name }}</span>
      </h5></template
    >
    <template v-slot:details>{{ this.price }}</template>
    <template v-slot:buttons>
      <AddToCartButton :product_id="id" :product_name="name" />
      <div v-if="isAuthenticated" class="btn-group">
        <EditItemButton :product_id="id" />
        <DeleteItemButton :product_id="id" />
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
    id: Number,
    name: String,
    price: Number,
    image: String,
  },
  methods: {
    showProduct(id) {
      return (window.location.href = "/products/" + id);
    },
  },
  computed: {
    isAuthenticated() {
      return this.$store.getters.getIsAuthenticated;
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
