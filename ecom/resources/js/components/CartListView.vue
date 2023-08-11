<template>
    <div>
        <Cart v-slot="{ cartItems, removeFromCart, productName, showQuantityInput, hideQuantityInput, formatQtyInputName, formatPrice, cartTotal, hasItems }">
            <div class="table-responsive">
            <table class="table table-striped table-hover" v-if="hasItems">
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
                    <td @click="showQuantityInput(cartItem, $event)">
                    <input
                        type="number"
                        class="form-control"
                        :name="formatQtyInputName(cartItem.product_id)"
                        :value="cartItem.qty"
                        v-if="cartItem.showQuantityField"
                        ref="qtyInput"
                        @blur="hideQuantityInput(cartItem, $event)"
                        @keyup.enter="hideQuantityInput(cartItem, $event)"
                        min="1"/>
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
