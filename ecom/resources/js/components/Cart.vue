<template>
  <div>
    <table class="table" v-if="this.items.length > 0">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in this.items" :key="index">
            <td>{{ JSON.parse(item.product_data).name }}</td>
            <td>{{ item.qty }}</td>
            <td>{{ formatPrice((JSON.parse(item.product_data).price*item.qty)) }}</td>
            <td><button @click="$parent.removeItem(index)" class="btn btn-danger w-100">Remove</button></td>
            </tr>
            <tr>
                <td>
                    Total: {{ true }} Ft
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
        }
    },
    methods: {
        async getItems(){
            await axios.get('cart/get').then( response =>{
                this.items = response['data']['items'];
            });
        },
        formatPrice(price) {
            return price.toLocaleString('hu-HU')+" Ft";
        },
    },
    async mounted() {
        await this.getItems();
    }
};
</script>
