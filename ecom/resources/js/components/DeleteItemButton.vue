<template>
  <ButtonBaseComponent
    class="btn-danger font-weight-bold"
    @click="deleteItem(product_id)"
  >
    <template #icon><i class="bi bi-trash pe-2"></i></template>
    <template #text>delete</template>
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
        async deleteItem(product_id) {
            await this.$swal
                .fire({
                    title: "Do you want to delete this item?",
                    showCancelButton: true,
                    confirmButtonText: "Delete",
                })
                .then(async (result) => {
                    if (result.isConfirmed) {
                        this.$store.dispatch('modifyIsLoading', true);
                        await axios
                            .delete("api/products/" + product_id)
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
