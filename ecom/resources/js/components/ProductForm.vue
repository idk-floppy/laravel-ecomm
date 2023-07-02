<template>
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
              required
              ref="image"
              :class="{ 'is-invalid': this.errors && this.errors.image }"
            />
          </div>
        </div>
        <div class="row py-2">
          <div class="col-sm col-md-3">
            <input type="submit" value="Create" class="btn btn-primary" />
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
</template>
<script>
import { submitProductForm } from "./services/SubmitProductForm";

export default {
  data() {
    return {
      name: "",
      price: 0,
      errors: null,
      success: null,
    };
  },
  methods: {
    async submitForm() {
      let formData = new FormData();
      formData.append("image", this.$refs.image.files[0]);
      formData.append("name", this.name);
      formData.append("price", this.price);
      let response = await submitProductForm(formData)
        .then((response) => {
          return response;
        })
        .catch((error) => {
          this.errors = error.response.data.errors;
          this.success = false;
        });

      if (response.success) {
        this.$swal
          .fire({
            icon: "success",
            title: "Success",
            text: "Product successfully created",
          })
          .then(() => {
            window.location.href = response.product;
          });
      }
    },
  },
};
</script>
