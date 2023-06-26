<template>
    <button class="btn btn-danger text-uppercase font-weight-bold" @click="deleteItem(product)">Delete</button>
</template>
<script>
export default {
    props: {
        product: Object,
    },
    methods: {
        async deleteItem(product) {
            await this.$swal.fire({
                title: 'Do you want to delete '+product.name+'?',
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then(async (result) => {
            if (result.isConfirmed) {
                await axios.delete('api/products/'+product.id)
                .then((response)=>{
                    if (response.data['success']) {
                        return window.location.href = window.location.origin;
                    }
                    return emitter.emit('requestErrorPopup');
                });
            }});
        },
    },
}
</script>
