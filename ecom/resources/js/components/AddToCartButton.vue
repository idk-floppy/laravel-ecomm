<template>
    <button class="btn btn-primary text-uppercase font-weight-bold" @click="addToCart(product)">add to cart</button>
</template>
<script>
export default {
    props: {
        product: Object,
    },
    methods: {
        async addToCart(item, method = "add") {
            await axios.post(
                'cart/add',
                {
                    product_data: JSON.stringify(item),
                    method: method,
                }).then(
                    response => {
                        console.log(response.data.success);
                        if (response.data.success) {
                            this.$toast.success(item.name + " added to cart");
                        } else {
                            this.$toast.error("Something went wrong!");
                        }
                    }).catch(error => {
                        this.$toast.error("Something went wrong!");
                    });
        },
    },
}
</script>
