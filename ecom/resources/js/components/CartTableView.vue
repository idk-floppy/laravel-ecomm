<template>
    <div>
        <Cart v-slot="{ cartItems, removeFromCart, productName, showQuantityInput, hideQuantityInput, formatQtyInputName, formatPrice, cartTotal, hasItems }">
            <div v-if="hasItems">
                <div class="table-responsive mb-1" id="cart-table">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th scope="col" class="col-1">#</th>
                            <th scope="col" class="col-2">Product</th>
                            <th scope="col" class="col-1">Quantity</th>
                            <th scope="col" class="col-2">Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(cartItem, index) in cartItems" :key="index">
                            <td>
                            <button
                                class="btn btn-danger"
                                @click="removeFromCart(cartItem.id)"
                            >
                                Remove
                            </button>
                            </td>
                            <td>{{ productName(cartItem) }}</td>
                            <td @click.stop="cartItem.showQuantityField ? '' : showQuantityInput(cartItem, $event)">
                                <input
                                type="number"
                                class="form-control"
                                :class="{ 'd-none': !cartItem.showQuantityField }"
                                :value="cartItem.qty"
                                ref="qtyInput"
                                @blur="hideQuantityInput(cartItem, $event)"
                                @keyup.enter="hideQuantityInput(cartItem, $event)"
                                min="1"/>
                            <p
                            class="text-quantity"
                            :class="{ 'd-none': cartItem.showQuantityField }"
                            >{{ cartItem.qty }}</p>
                            </td>
                            <td>
                            {{ formatPrice(cartItem.subtotal) }} ({{ formatPrice(cartItem.product.price) }})
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <p class="float-start">Total: {{ formatPrice(cartTotal) }}</p>
            </div>
            <div v-else>Cart is empty</div>
        </Cart>
    </div>
</template>
<script>
import Cart from './Cart.vue';

export default {
    components: {
        Cart
    },
}
</script>
<style scoped>
#cart-table {
    max-height: max(400px, 50vh);
}
</style>
