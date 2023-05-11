<template>
    <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ product.name }}</h5>
                        <p class="card-text">{{ product.price }} Ft</p>
                        <div class="hstack gap-2">
                                <a class="btn btn-primary text-uppercase font-weight-bold"
                                    @click="addToCart(product)">add to cart</a>
                                <a v-if="user" class="btn btn-warning text-uppercase font-weight-bold" @click="editProduct(product.id)">Edit</a>
                        </div>
                    </div>
                </div>
</template>
<script>
export default {
    props:{
        product: Object,
    },
    inject: ['user'],
    methods:{
        addToCart(product) {
            window.mitt.emit('addToCart', product);
        },
        editProduct(id) {
            return window.location.href = "/products/" + id+"/edit";
        }
    }
}
</script>
