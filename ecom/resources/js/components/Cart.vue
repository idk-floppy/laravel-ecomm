<template>
  <div>
    <loading-overlay v-if="loading"></loading-overlay>

    <div class="table-responsive">
      <table
        class="table table-striped table-hover"
        v-if="hasItems"
      >
        <thead>
          <tr>
            <th scope="col" class="col-1">#</th>
            <th scope="col" class="col-2">Product</th>
            <th scope="col" class="col-1">Quantity</th>
            <th scope="col" class="col-1">Piece-Price</th>
            <th scope="col" class="col-2">Price</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(cartItem, index) in this.items" :key="index">
            <td>
              <button
                class="btn btn-danger"
                @click="removeFromCart(cartItem.product_id)"
              >
                Remove
              </button>
            </td>
            <td>{{ productName(cartItem) }}</td>
            <td @click="showQuantityInput(cartItem)">
              <input
                type="number"
                class="form-control"
                :name="formatQtyInputName(cartItem.product_id)"
                :value="cartItem.qty"
                v-if="cartItem.showQuantityField"
                ref="qtyInput"
                @blur="hideQuantityInput(cartItem)"
                @keyup.enter="hideQuantityInput(cartItem)"
                min="1"
              />
              <p v-else>{{ cartItem.qty }}</p>
            </td>
            <td>
              {{ formatPrice(productPrice(cartItem)) }}
            </td>
            <td>
              {{ formatPrice(productPrice(cartItem) * cartItem.qty) }}
            </td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Total</td>
            <td>{{ formatPrice(total) }}</td>
          </tr>
        </tbody>
      </table>
      <div v-else>Cart is empty</div>
    </div>
  </div>
</template>

<script>
import { addToCart } from "./services/AddToCartService";

export default {
  data() {
    return {
      items: [],
      total: 0,
      loading: true,
    };
  },
  methods: {
    async getItems() {
      await axios.get("cart/get").then((response) => {
        if (Object.keys(response.data).length > 0) {
          this.items = response["data"]["items"].map((item) => ({
            ...item,
            showQuantityField: false,
          }));
        }
      });
      return 0;
    },
    async calculateTotal() {
      let total = 0;

      for (let index = 0; index < this.items.length; index++) {
        const item = this.items[index];
        try {
          const data = JSON.parse(item.product_data);
          if (data && typeof data.price === "number") {
            total += item.qty * data.price;
          } else {
            throw new Error("Invalid product data");
          }
        } catch (error) {
          console.error("Something went wrong!");
          console.error(error);
          return emitter.emit("requestErrorPopup");
        }
      }
      this.total = total;
    },
    async removeFromCart(product_id) {
        this.loading = true;
      await axios
        .post("cart/remove", { product_id: product_id })
        .then(async (response) => {
          if (!response.data["success"]) {
            this.loading = false;
            return emitter.emit("requestErrorPopup");
          }
            await this.getItems();
            this.loading = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    formatQtyInputName(id) {
      return String(id) + "-qty";
    },

    showQuantityInput(item) {
      item.showQuantityField = true;
      this.$nextTick(() => {
        this.$refs.qtyInput[0].focus();
      });
    },
    async hideQuantityInput(item) {
      let newQty = this.$refs.qtyInput[0].value;
      item.showQuantityField = false;
      if (newQty != item.qty) {
        await this.updateQuantityByField(item.product_data, newQty);
        await this.calculateTotal();
      }
    },
    async updateQuantityByField(product, qty) {
      this.loading = true;
      let productToUpdate = JSON.parse(product);
      let response = await addToCart(productToUpdate.id, qty, "set");
      if (response) {
        this.$toast.success(productToUpdate.name + " updated");
      } else {
        emitter.emit("requestErrorPopup");
      }
      await this.getItems();
      this.loading = false;
    },
    formatPrice(price) {
      return price.toLocaleString("hu-HU") + " Ft";
    },
  },
  computed: {
    hasItems: function () {
        return (this.items.length > 0)
    },
    productName: function () {
      return function (cartItem) {
        return JSON.parse(cartItem.product_data).name;
      };
    },
    productPrice: function () {
      return function (cartItem) {
        return JSON.parse(cartItem.product_data).price;
      };
    },
  },
  async mounted() {
    await this.getItems();
    await this.calculateTotal();
    this.loading = false;
  },
};
</script>
