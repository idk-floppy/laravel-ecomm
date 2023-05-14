<template>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ product.name }}</h5>
            <p class="card-text">{{ product.price }} Ft</p>
            <div class="hstack gap-2">
                <AddToCartButton :product="product" />
                <a v-if="user" class="btn btn-warning text-uppercase font-weight-bold"
                    @click="editProduct(product.id)">Edit</a>
            </div>
        </div>
    </div>
</template>
<script>
import AddToCartButton from './AddToCartButton.vue';

export default {
    components: {
        AddToCartButton,
    },
    props: {
        product: Object,
    },
    inject: ["user"],
    methods: {
        editProduct(id) {
            return window.location.href = "/products/" + id + "/edit";
        }
    },
    components: {
        AddToCartButton
    }
}

</script>
