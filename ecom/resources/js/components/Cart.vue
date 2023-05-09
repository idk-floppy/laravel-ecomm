<template>
    <div v-if="loading" class="spinner-container">
        <div class="spinner-border" role="status">
    </div>
    </div>
    <div>
        <table class="table" v-if="this.items.length > 0">
            <thead>
                <tr>
                    <th scope="col" class="col-2">Product</th>
                    <th scope="col" class="col-1">Quantity</th>
                    <th scope="col" class="col-2">Piece-Price</th>
                    <th scope="col" class="col-2">Price</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in this.items" :key="index">
                    <td>{{ JSON.parse(item.product_data).name }}</td>
                    <td @click="showQuantityInput(item)">
                        <input type="number" class="form-control" :name="formatQtyInputName(item.product_id)"
                            :value="item.qty" v-if="item.showQuantityField" ref="qtyInput"
                            @blur="hideQuantityInput(item)" @keyup.enter="hideQuantityInput(item)">
                        <p v-else>{{ item.qty }}</p>
                    </td>
                    <td>{{ formatPrice(JSON.parse(item.product_data).price) }}</td>
                    <td>{{ formatPrice((JSON.parse(item.product_data).price * item.qty)) }}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td></td>
                    <td></td>
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
                console.log(response);
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
            this.total = 0;
            this.items.forEach(item => {
                this.total += item.qty * JSON.parse(item.product_data).price;
            });
            return 0;
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
            item.showQuantityField = false;
            if (this.$refs.qtyInput[0].value != item.qty){
                await this.updateQuantityByField(item.product_data, this.$refs.qtyInput[0].value);
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
    async mounted() {
        await this.getItems();
        await this.calculateTotal();
        this.loading = false;
    }
};

</script>
<style scoped>
.spinner-container{
    position: absolute;
    left:0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.25);
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
