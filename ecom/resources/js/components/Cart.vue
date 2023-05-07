<template>
  <div v-if="loading">
    <p>Loading...</p>
</div>
  <div v-else>
    <table class="table" v-if="this.items.length > 0">
        <thead>
            <tr>
                <th scope="col" class="col-2">Product</th>
                <th scope="col" class="col-1">Quantity</th>
                <th scope="col" class="col-2">Price</th>
                <th scope="col" class="col-1"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in this.items" :key="index">
            <td>{{ JSON.parse(item.product_data).name }}</td>
            <td @click="showQuantityInput(item)">
                <input type="number" class="form-control" :name="formatQtyInputName(item.product_id)" :value="item.qty" v-if="item.showQuantityField" ref="qtyInput" @blur="hideQuantityInput(item)" @keyup.enter="hideQuantityInput(item)">
                <p v-else>{{ item.qty }}</p>
            </td>
            <td>{{ formatPrice((JSON.parse(item.product_data).price*item.qty)) }}</td>
            <td><button @click="$parent.removeItem(index)" class="btn btn-danger w-100">Remove</button></td>
            </tr>
            <tr>
                <td>
                    Total: {{ total }} Ft
                </td>
                    <td></td>
                <td>
                </td>
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
    data(){
        return {
            items: [],
            total: 0,
            loading: true,
        }
    },
    methods: {
        async getItems(){
            this.loading = true;
            await axios.get('cart/get').then( response =>{
                console.log(response);
                if (Object.keys(response.data).length > 0) {
                    this.items = response['data']['items'].map(item =>({
                        ...item, showQuantityField: false,
                    }));
                }
                this.loading = false;
            });
            return 0;
        },
        async calculateTotal(){
            this.total = 0;
            this.items.forEach(item => {
                this.total+= item.qty * JSON.parse(item.product_data).price;
            });
            return 0;
        },
        /**
         * It's called in vue components, e.g. :name="formatQtyInputName(item.product_id)"
         * @param {integer} id the product_id from the item object.
         */
        formatQtyInputName(id){
            return String(id)+'-qty';
        },
        /**
         *
         * @param {Object} item that has: id, cart_data_id, product_id, qty, product_data(json).
         */
        showQuantityInput(item){
            item.showQuantityField = true;
            this.$nextTick(()=>{
                this.$refs.qtyInput[0].focus();
            });
        },
        /**
         *
         * @param {Object} item that has: id, cart_data_id, product_id, qty, product_data(json).
         */
        async hideQuantityInput(item){
            item.showQuantityField = false;
            await this.updateQuantityByField(item.product_data, this.$refs.qtyInput[0].value);
            await this.calculateTotal();
        },
        async updateQuantityByField(item, qty){
            await axios.post('cart/add', {
                product_data: item,
                qty: qty
            });
            await this.getItems();
        },
        formatPrice(price) {
            return price.toLocaleString('hu-HU')+" Ft";
        },
    },
    async mounted() {
        await this.getItems();
        await this.calculateTotal();
    }
};
</script>
