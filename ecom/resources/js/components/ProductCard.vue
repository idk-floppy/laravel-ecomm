<template>
    <CardBase>
        <template v-slot:image><img :src="getProductImage(product.image)" class="card-img-top" /></template>
            <template v-slot:title><h5 class="card-title"><span role="button" @click="showProduct(product.id)">{{ product.name }} ({{ product.id }})</span></h5></template>
            <template v-slot:details>{{ product.price }} Ft</template>
            <template v-slot:buttons>
                <AddToCartButton :product="product" />
                <div v-if="user" class="btn-group">
                    <EditItemButton :product="product" />
                    <DeleteItemButton :product="product" />
                </div>
            </template>
    </CardBase>
</template>
<script>
import AddToCartButton from './AddToCartButton.vue';
import EditItemButton from './EditItemButton.vue';
import DeleteItemButton from './DeleteItemButton.vue';
import CardBase from './CardBase.vue';

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
    inject: ["user"],
    methods: {
        showProduct(id) {
            return window.location.href = "/products/" + id;
        },
        getProductImage(image) {
            if (image) {
                return window.location.origin + '/storage/' + image;
            }
            return null;
        }
    },
}

</script>
<style scoped>
.card-img-top {
    object-fit: cover;
    object-position: center;
    height: clamp(300px, 350px, 450px);
}
</style>
