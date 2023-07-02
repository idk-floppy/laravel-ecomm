<template>
  <ButtonBaseComponent
    class="btn-secondary font-weight-bold"
    @click="editItem(product)"
  >
    <template #icon><i class="bi bi-pencil-square pe-2"></i></template>
    <template #text>edit</template>
  </ButtonBaseComponent>
</template>
<script>
import ButtonBaseComponent from "./ButtonBaseComponent.vue";

export default {
  props: {
    product: Object,
  },
  components: {
    ButtonBaseComponent,
  },
  methods: {
    editItem(product) {
      return (window.location.href = "/products/" + product.id + "/edit");
    },
  },
};
</script>
