<template>
    <div class="card">
        <img :src="getProductImage(product.image)" class="card-img-top" />
        <p></p>
        <div class="card-body">
            <h5 class="card-title"><span role="button" @click="showProduct(product.id)">{{ product.name }} ({{ product.id }})</span></h5>
            <h6 class="card-subtitle mb-2 text-muted">{{ product.price }} Ft</h6>
            <div class="row row-cols-1 g-2">
                <AddToCartButton :product="product" />
                <div v-if="user" class="btn-group">
                    <EditItemButton :product="product" />
                    <DeleteItemButton :product="product" />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import AddToCartButton from './AddToCartButton.vue';
import EditItemButton from './EditItemButton.vue';
import DeleteItemButton from './DeleteItemButton.vue';

export default {
    components: {
        AddToCartButton,
        EditItemButton,
        DeleteItemButton,
    },
    props: {
        product: Object,
    },
    inject: ["user"],
    methods: {
        showProduct(id) {
            return window.location.href = "/products/" + id;
        },
        getProductImage(image){
            if (image) {
                return window.location.origin+'/storage/'+image;
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
