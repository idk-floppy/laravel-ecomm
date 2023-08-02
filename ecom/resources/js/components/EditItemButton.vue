<template>
  <ButtonBaseComponent
    class="btn-secondary font-weight-bold"
    @click="editItem(product_id)"
  >
    <template #icon><i class="bi bi-pencil-square pe-2"></i></template>
    <template #text>edit</template>
  </ButtonBaseComponent>
</template>
<script>
import ButtonBaseComponent from "./ButtonBaseComponent.vue";

export default {
  props: {
    product_id: Number,
  },
  components: {
    ButtonBaseComponent,
  },
  methods: {
    editItem(product_id) {
      return (window.location.href = "/products/" + product_id + "/edit");
    },
  },
};
</script>
