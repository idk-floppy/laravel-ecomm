<template>
  <div>
    <loading-overlay v-if="loading"></loading-overlay>

    <div>
    <form
      method="post"
      enctype="multipart/form-data"
      @submit.prevent="submitForm"
    >
      <div class="col">
        <div class="row py-2">
          <div class="col">
            <div class="form-floating">
              <input
                type="text"
                v-model="name"
                name="name"
                id="name"
                class="form-control"
                :class="{ 'is-invalid': this.errors && this.errors.name }"
                required
                placeholder="Name"
              />
              <label for="name">Name</label>
            </div>
          </div>
          <div class="col">
            <div class="form-floating">
              <input
                type="number"
                v-model="price"
                name="price"
                id="price"
                class="form-control"
                :class="{ 'is-invalid': this.errors && this.errors.price }"
                min="0"
                required
                placeholder="Price"
              />
              <label for="price">Price</label>
            </div>
          </div>
        </div>
        <div class="row py-2">
          <div class="col">
            <label for="image">Image</label>
            <input
              type="file"
              name="image"
              id="image"
              class="form-control"
              :required="isCreateMode"
              ref="image"
              :class="{ 'is-invalid': this.errors && this.errors.image }"
            />
          </div>
        </div>
        <div class="row py-2">
          <div class="col-sm col-md-3">
            <input type="submit" :value="isCreateMode ? 'Create' : 'Save'" class="btn btn-primary" />
          </div>
        </div>
      </div>
    </form>
    <div v-if="this.errors" class="alert alert-danger">
      <div v-for="error in errors" v-bind:key="error">
        <a>{{ error[0] }}</a>
      </div>
    </div>
</div>
  </div>
</template>
<script>
import { submitEditProductForm } from './services/SubmitEditProductForm';
import { submitProductForm } from "./services/SubmitProductForm";

export default {
    props: {
        isCreateMode: {
            type: Boolean,
            required: true,
            default: true
        },
        productId: {
            type: Number,
        }
    },
  data() {
    return {
        name: "",
        price: 0,
        errors: null,
        loading: true
    };
  },
  async created(){
    if (!this.isCreateMode)
        await this.fetchProductData(this.productId);
    this.loading = false;
},
  methods: {
    async fetchProductData(productId){
        axios.get(`api/products/${productId}`)
        .then((response)=>{
            this.name = response['data']['name'];
            this.price = response['data']['price'];
        });
    },
    async submitForm() {
        this.loading = true;
        let formData = new FormData();
        if (typeof this.$refs.image.files[0] !== "undefined") {
            formData.append("image", this.$refs.image.files[0]);
        }
        formData.append("name", this.name);
        formData.append("price", this.price);
        if (!this.isCreateMode)
            formData.append("_method", "PATCH");
        let response;

        try {
        response = this.isCreateMode ? await submitProductForm(formData) : await submitEditProductForm(formData, this.productId);
        } catch (error) {
            console.log(error.response.data.errors);
            this.errors = error.response.data.errors;
            this.loading = false;
            return 0;
        } finally{
            this.loading = false;
        }
        this.$swal
            .fire({
                icon: "success",
                title: "Success",
                text: "Product successfully created",
            })
            .then(() => {
                window.location.href = response.product;
            });
    },
},
};
</script>
