<template>
<div class="container">
    <div class="row">
            <div class="input-group">
                <input id="search" v-model="searchTerm" @input="filterProducts" class="w-100 card mb-3 p-2" placeholder="Keresés">
            </div>
    </div>
    <div class="row row-cols-1 row-cols-md-3 g-2">
        <div class="col-md-4 mb-3" v-for="product in products" :key="product.id" :product="product">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ product.name }}</h5>
                    <p class="card-text">{{ product.price }} Ft</p>
                    <a class="btn btn-primary text-uppercase font-weight-bold" @click="buy(product)">add to cart</a>
                </div>
            </div>
        </div>
    </div>

    <div v-if="products.length < 1">
        <br>
        <p>No results...</p>
    </div>
    <nav>
            <ul class="pagination justify-content-center mt-5">
            <li class="page-item" :class="{ 'disabled': !links.prev }">
            <a class="page-link" @click.prevent="getProducts(searchTerm, links.prev)" href="#">Previous</a>
            </li>
            <li class="page-item">
                <span class="page-link">{{ meta.current_page }}/{{ meta.last_page }}</span>
            </li>
            <li class="page-item" :class="{ 'disabled': !links.next }">
            <a class="page-link" @click.prevent="getProducts(searchTerm, links.next)" href="#">Next</a>
            </li>
        </ul>
    </nav>
</div>
</template>
<script>
export default {
    emits: ['buy'],
    data() {
        return {
            products: [],
            meta: {},
            links: {},
            searchTerm: '',
        }
    },
    methods: {
        async getProducts(searchTerm = '', url = '/api/products') {
            await axios.get(url, {
                params: {
                    search: searchTerm,
                }
            })
                .then(response => {
                    this.products = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;
                });
        },
        filterProducts() {
            return this.getProducts(this.searchTerm);
        },
        buy(product) {
            window.mitt.emit('buy', product);
        },
    },
    mounted() {
        this.getProducts();
    },
}
</script>
