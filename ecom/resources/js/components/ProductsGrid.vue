<template>
  <div>
    <div class="container">
      <div class="row mb-4 g-0">
        <div class="card">
          <div class="card-body">
            <div class="row row-cols-md-4 row-cols-1 g-3">
              <div class="col">
                <input
                  type="text"
                  id="search"
                  v-model="searchTerm"
                  class="form-control p-2"
                  placeholder="Search"
                  @keydown.enter="getProducts()"
                />
              </div>
              <div class="col">
                <div class="input-group">
                  <input
                    type="number"
                    name="minPrice"
                    id="minPrice"
                    v-model="minPrice"
                    class="form-control p-2"
                    placeholder="min ft"
                    @keydown.enter="getProducts()"
                  />
                  <input
                    type="number"
                    name="maxPrice"
                    id="maxPrice"
                    v-model="maxPrice"
                    class="form-control p-2"
                    placeholder="max ft"
                    @keydown.enter="getProducts()"
                  />
                </div>
              </div>
              <div class="col">
                <select
                  name="orderBy"
                  id="orderBy"
                  v-model="orderBy"
                  class="form-select"
                >
                  <option value="name">Order by name</option>
                  <option value="price">Order by price</option>
                </select>
              </div>
              <div class="col">
                <input
                  type="button"
                  value="Search"
                  class="btn btn-primary w-100"
                  @click="getProducts()"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
        <div v-for="product in products" :key="product.id" :product="product">
          <product-card
            :id="product.id"
            :name="product.name"
            :price="product.price_display"
            :image="product.image"
          ></product-card>
        </div>
      </div>

      <div v-if="products.length < 1">
        <br />
        <p>No results...</p>
      </div>
      <nav>
        <ul class="pagination justify-content-center">
          <li class="page-item" :class="{ disabled: !links.prev }">
            <a
              class="page-link"
              @click.prevent="getProducts(links.prev)"
              href="#"
              >Previous</a
            >
          </li>
          <li class="page-item">
            <span class="page-link"
              >{{ meta.current_page }}/{{ meta.last_page }}</span
            >
          </li>
          <li class="page-item" :class="{ disabled: !links.next }">
            <a
              class="page-link"
              @click.prevent="getProducts(links.next)"
              href="#"
              >Next</a
            >
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      products: [],
      meta: {},
      links: {},
      searchTerm: "",
      orderBy: "name",
      minPrice: null,
      maxPrice: null,
      loading: true,
    };
  },
  methods: {
    async getProducts(url = "/api/products") {
      console.log("url");
      console.log(url);
      this.$store.dispatch("modifyIsLoading", true);
      await axios
        .get(url, {
          params: {
            name: this.searchTerm,
            orderBy: this.orderBy,
            minPrice: this.minPrice,
            maxPrice: this.maxPrice,
          },
        })
        .then((response) => {
          this.products = response.data.data;
          this.meta = response.data.meta;
          this.links = response.data.links;
        });
      this.$store.dispatch("modifyIsLoading", false);
    },
  },
  mounted() {
    this.getProducts();
  },
};
</script>
