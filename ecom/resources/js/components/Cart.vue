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
          <tr v-for="(cartItem, index) in this.cartItems" :key="index">
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
              {{ formatPrice(cartItem.subtotal) }}
            </td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Total</td>
            <td>{{ formatPrice(cartTotal) }}</td>
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
        async removeFromCart(cartitem_id) {
            this.loading = true;
            let response = await axios.post("cart/remove", { cartitem_id: cartitem_id });
            if (response.data.success) {
                this.$store.dispatch('updateCartItems', response.data.cart);
                this.loading = false;
                emitter.emit("flashToast", { icon: 'success', title: `Cart successfully updated` });
            }
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
            }
        },
        async updateQuantityByField(item, qty) {
            this.loading = true;
            let response = await addToCart(item.product.id, qty, "set");
            if (!response.data.success) {
                this.loading = false;
                emitter.emit("requestErrorPopup");
                return 0;
            }

            this.$store.dispatch('updateCartItems', response.data.cart);
            this.loading = false;
            emitter.emit("flashToast", { icon: 'success', title: `${item.product.name} successfully updated` });
        },
        formatPrice(price) {
            return price.toLocaleString("hu-HU") + " Ft";
        },
    },
    computed: {
        hasItems() {
            return this.$store.getters.getCartItemCount;
        },
        productName: function () {
            return function (cartItem) {
                return cartItem.product.name;
            };
        },
        cartItems() {
            return this.$store.getters.getCartItems;
        },
        cartTotal(){
            return this.$store.getters.getCartTotal;
        }
    },
    async mounted() {
        this.loading = false;
    },
};
</script>
