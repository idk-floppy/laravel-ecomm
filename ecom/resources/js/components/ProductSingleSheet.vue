<template>
    <div>
        <product-card :id="product_id" :name="name" :price="price" :image="image"></product-card>
    </div>
</template>
<script>
export default {
    data(){
        return {
            name: '',
            price: 0,
            image: null,
        }
    },
    props: {
        product_id: Number,
    },
    methods:{
        async fetchProduct(product_id){
            let response = await axios.get(`api/products/${product_id}`)
            .then((resp)=>{
                return resp;
            });

            return response;
        }
    },
    async created() {
        await this.fetchProduct(this.product_id)
        .then(async (response)=>{
            this.name = response.data.product.name;
            this.price = response.data.product.price;
            this.image = response.data.product.image;
        });
    },
}
</script>
