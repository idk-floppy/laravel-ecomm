<template>
    <div v-if="loading" class="spinner-container">
        <div class="spinner-border" role="status">
        </div>
    </div>
    <div>
        <table class="table table-striped table-hover" v-if="this.items.length > 0">
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
                    <td><button class="btn btn-danger" @click="removeFromCart(cartItem.id)">Remove</button></td>
                    <td>{{ productName(cartItem) }} ({{ productId(cartItem) }})</td>
                    <td @click="showQuantityInput(cartItem)">
                        <input type="number" class="form-control" :name="formatQtyInputName(cartItem.product_id)"
                            :value="cartItem.qty" v-if="cartItem.showQuantityField" ref="qtyInput"
                            @blur="hideQuantityInput(cartItem)" @keyup.enter="hideQuantityInput(cartItem)">
                        <p v-else>{{ cartItem.qty }}</p>
                    </td>
                    <td>{{ formatPrice(productPrice(cartItem)) }}</td>
                    <td>{{ formatPrice((productPrice(cartItem) * cartItem.qty)) }}</td>
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
        <div v-else>
            Cart is empty
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            items: [],
            total: 0,
            loading: true,
        }
    },
    methods: {
        async getItems() {
            await axios.get('cart/get').then(response => {
                if (Object.keys(response.data).length > 0) {
                    this.items = response['data']['items'].map(item => ({
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
                    if (data && typeof data.price === 'number') {
                        total += item.qty * data.price;
                    } else {
                        throw new Error('Invalid product data');
                    }
                } catch (error) {
                    console.error('Something went wrong!');
                    console.error(error);
                    return emitter.emit('requestErrorPopup');
                }
            }
            this.total = total;
        },
        async removeFromCart(id){
            await axios.post('cart/remove',{id: id})
            .then((response)=>{
                if (!response.data['success']) {
                    return emitter.emit('requestErrorPopup');
                }
                return window.location.reload();
            }).catch((error)=>{
                console.log(error);
            });
        },
        /**
         * It's called in vue components, e.g. :name="formatQtyInputName(item.product_id)"
         * @param {integer} id the product_id from the item object.
         */
        formatQtyInputName(id) {
            return String(id) + '-qty';
        },
        /**
         *
         * @param {Object} item that has: id, cart_data_id, product_id, qty, product_data(json).
         */
        showQuantityInput(item) {
            item.showQuantityField = true;
            this.$nextTick(() => {
                this.$refs.qtyInput[0].focus();
            });
        },
        /**
         *
         * @param {Object} item that has: id, cart_data_id, product_id, qty, product_data(json).
         */
        async hideQuantityInput(item) {
            let newQty = this.$refs.qtyInput[0].value;
            item.showQuantityField = false;
            if (newQty != item.qty) {
                await this.updateQuantityByField(item.product_data, newQty);
                await this.calculateTotal();
            }
        },
        async updateQuantityByField(item, qty) {
            this.loading = true;
            await axios.post('cart/add', {
                product_data: item,
                qty: qty,
                method: 'set'
            });
            await this.getItems();
            this.loading = false;
        },
        formatPrice(price) {
            return price.toLocaleString('hu-HU') + " Ft";
        },
    },
    computed: {
        productId: function () {
            return function (cartItem) {
                return JSON.parse(cartItem.product_data).id;
            };
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
    }
};

</script>
<style scoped>
.spinner-container {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.25);
}

.spinner-border {
    width: 50px;
    height: 50px;
    position: absolute;
    left: 50%;
    top: 50%;
    margin: -25px 0 0 -25px;
}
</style>
