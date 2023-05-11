<template>
    <div class="container">
        <div class="row">
            <div class="input-group">
                <input id="search" v-model="searchTerm" v-debounce="filterProducts" class="w-100 card mb-3 p-2"
                    placeholder="Keresés">
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col-md-4" v-for="product in products" :key="product.id" :product="product">
                <Card :product="product"></Card>
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
import Card from './Card.vue';

export default {
    emits: ['addToCart'],
    components:{
        Card,
    },
    data() {
        return {
            products: [],
            meta: {},
            links: {},
            searchTerm: '',
        }
    },
    methods: {
        async getProducts(searchTerm = '', url = '/products') {
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
    },
    mounted() {
        this.getProducts();
    },
}

</script>
