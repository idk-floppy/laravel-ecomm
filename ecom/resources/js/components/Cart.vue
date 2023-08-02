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
                @click="removeFromCart(cartItem.id)"
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
              {{ formatPrice(cartItem.product.price) }}
            </td>
            <td>
              {{ formatPrice(cartItem.product.price * cartItem.qty) }}
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

      this.items.forEach(item => {
        total += item.qty * item.product.price;
      });

      this.total = total;
    },
    async removeFromCart(cartitem_id) {
        this.loading = true;
      await axios
        .post("cart/remove", { cartitem_id: cartitem_id })
        .then(async (response) => {
          if (!response.data["success"]) {
            this.loading = false;
            return emitter.emit("requestErrorPopup");
          }
            await this.getItems();
            await this.calculateTotal();
            this.loading = false;
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
        await this.updateQuantityByField(item, newQty);
        await this.calculateTotal();
      }
    },
    async updateQuantityByField(item, qty) {
      this.loading = true;
      let response = await addToCart(item.product.id, qty, "set");
      if (!response) {
        await this.getItems();
        this.loading = false;
        emitter.emit("requestErrorPopup");
        return 0;
      }

      await this.getItems();
      this.loading = false;
      this.$toast.success(item.product.name + " updated");
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
        return cartItem.product.name;
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
