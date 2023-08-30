<template>
  <div>
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
                accept="image/jpeg"
                :required="isCreateMode"
                ref="image"
                :class="{ 'is-invalid': this.errors && this.errors.image }"
              />
            </div>
          </div>
          <div class="row py-2">
            <div class="col-sm col-md-3">
              <input
                type="submit"
                :value="isCreateMode ? 'Create' : 'Save'"
                class="btn btn-primary"
              />
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
import { submitProductForm } from "./services/SubmitProductForm";

export default {
    props: {
        isCreateMode: {
            type: Boolean,
            required: true,
            default: true,
        },
        productId: {
            type: Number,
            default: null,
        },
    },
    data() {
        return {
            name: "",
            price: 0,
            errors: null,
        };
    },
    async created() {
        this.$store.dispatch('modifyIsLoading', true);
        if (!this.isCreateMode) await this.fetchProductData(this.productId);
        this.$store.dispatch('modifyIsLoading', false);
    },
    methods: {
        async fetchProductData(productId) {
            axios.get(`api/products/${productId}`).then((response) => {
                this.name = response.data.product.name;
                this.price = response.data.product.price;
            })
                .catch((resp) => {
                    history.pushState(null, null, null);
                    emitter.emit('requestErrorPopup', {
                        "message": resp.response.data.message,
                        "callback": () => {
                            emitter.emit('');
                            return window.location.href = window.location.origin;
                        }
                    });
                });
        },
        async submitForm() {
            this.$store.dispatch('modifyIsLoading', true);
            let formData = new FormData();
            if (typeof this.$refs.image.files[0] !== "undefined") {
                formData.append("image", this.$refs.image.files[0]);
            }
            formData.append("name", this.name);
            formData.append("price", this.price);
            if (!this.isCreateMode) formData.append("_method", "PATCH");
            let response;

            try {
                response = await submitProductForm(formData, this.productId);
            } catch (error) {
                this.errors = error.response.data.errors;
                this.$store.dispatch('modifyIsLoading', false);
                emitter.emit("requestErrorPopup", {
                    message: error.response.data.message,
                });
                return 0;
            } finally {
                this.$store.dispatch('modifyIsLoading', false);
            }
            emitter.emit("requestSuccessPopup", {
                message: this.isCreateMode ? "Product created" : "Product updated",
                callback: () => {
                    window.location =
                        window.location.origin + "/products/" + response.data.id;
                },
            });
        },
    },
};
</script>
