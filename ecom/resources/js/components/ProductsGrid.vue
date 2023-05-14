<template>
    <div class="container">
        <div class="row mb-4 g-0">
            <div class="card">
                <div class="card-body">
                    <div class="row row-cols-md-3 row-cols-1 g-3">
                        <div class="col col-sm-12">
                                <input type="text" id="search" v-model="searchTerm" v-debounce="filterProducts"
                                class="form-control p-2" placeholder="Search">
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <input type="number" name="range1" id="range1" class="form-control p-2" placeholder="min ft">
                                <input type="number" name="range2" id="range2" class="form-control p-2" placeholder="max ft">
                            </div>
                        </div>
                        <div class="col">
                            <input type="button" value="Search" class="btn btn-primary w-100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
            <div v-for="product in products" :key="product.id" :product="product">
                <Card :product="product"></Card>
            </div>
        </div>

        <div v-if="products.length < 1">
            <br>
            <p>No results...</p>
        </div>
        <nav>
            <ul class="pagination justify-content-center">
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
    components: {
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
