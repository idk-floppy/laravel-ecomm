<template>
<div class="container my-5">
    <label for="search">Keresés:</label>
    <input id="search" v-model="searchTerm" @input="getFilteredProducts">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col-md-4 mb-3" v-for="product in filteredProducts" :key="product.id" :product="product">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ product.name }}</h5>
                    <p class="card-text">{{ product.description }}</p>
                    <p class="card-text">{{ product.price }}</p>
                    <a class="btn btn-primary" @click="buy(product)">Buy</a>
                </div>
            </div>
        </div>
    </div>

    <div v-if="filteredProducts.length < 1">
        <br>
        <p>Nincs találat...</p>
    </div>
    <nav>
            <ul class="pagination justify-content-center mt-5">
            <li class="page-item" :class="{ 'disabled': !links.prev }">
            <a class="page-link" @click.prevent="getProducts(links.prev)" href="#">Előző</a>
            </li>
            <li class="page-item">
                <span class="page-link">{{ meta.current_page }}/{{ meta.last_page }}</span>
            </li>
            <li class="page-item" :class="{ 'disabled': !links.next }">
            <a class="page-link" @click.prevent="getProducts(links.next)" href="#">Következő</a>
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
    computed: {
        filteredProducts() {
            return this.products.filter((product) => {
                return product.name.toLowerCase().includes(this.searchTerm.toLowerCase()) || product.description.toLowerCase().includes(this.searchTerm.toLowerCase());
            });
        },
    },
    methods: {
        getProducts(url = '/api/products', searchTerm = '') {
            axios.get(url, {
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
        getFilteredProducts() {
            this.getProducts("/api/products", this.searchTerm);
        },
        buy(product) {
            this.emitter.emit('buy', product);
        },
    },
    mounted() {
        this.getProducts();
    },
}
</script>
