<template>
    <div class="container">
      <div class="row">
        <div class="col-md-4" v-for="product in products" :key="product.id">
          <div class="card mb-4">
            <!-- <img :src="product.image" class="card-img-top" alt="..."> -->
            <div class="card-body">
              <h5 class="card-title">{{ product.name }}</h5>
              <p class="card-text">{{ product.description }}</p>
              <a href="#" class="btn btn-primary">Kosárba</a>
            </div>
          </div>
        </div>
      </div>
      <nav>
      <ul class="pagination">
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
    data(){
        return {
            products: [],
            meta: {},
            links: {}
        }
    },
    methods: {
        getProducts(url = '/api/products') {
            axios.get(url)
                .then(response => {
                this.products = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
        });
        },
    },
    mounted(){
        this.getProducts();
    },
}
</script>
