<template>
    <button class="btn btn-default text-uppercase font-weight-bold" @click="editItem(product)">Edit</button>
</template>
<script>
export default {
    props: {
        product: Object,
    },
    methods: {
        editItem(product){
            return window.location.href = "/products/" + product.id + "/edit";
        }
    },
}
</script>
