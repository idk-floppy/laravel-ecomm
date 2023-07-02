<template>
  <ButtonBaseComponent
    class="btn-danger font-weight-bold"
    @click="deleteItem(product)"
  >
    <template #icon><i class="bi bi-trash pe-2"></i></template>
    <template #text>delete</template>
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
    async deleteItem(product) {
      await this.$swal
        .fire({
          title: "Do you want to delete " + product.name + "?",
          showCancelButton: true,
          confirmButtonText: "Delete",
        })
        .then(async (result) => {
          if (result.isConfirmed) {
            await axios
              .delete("api/products/" + product.id)
              .then((response) => {
                if (response.data["success"]) {
                  return (window.location.href = window.location.origin);
                }
                return emitter.emit("requestErrorPopup");
              });
          }
        });
    },
  },
};
</script>
