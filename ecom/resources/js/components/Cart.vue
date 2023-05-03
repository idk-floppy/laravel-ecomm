<template>
  <div>
    <table class="table" v-if="$parent.cartItemsCount > 0">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in $parent.cartItems" :key="index">
            <td>{{ item.name }}</td>
            <td>{{ item.price }} Ft</td>
            <td><button @click="$parent.removeItem(index)" class="btn btn-danger w-100">Remove</button></td>
            </tr>
            <tr>
                <td>
                    Total: {{ $parent.cartTotalPrice }} Ft
                </td>
                    <td></td>
                <td>
                    <button @click="$parent.clearCart" class="btn btn-danger w-100">Clear cart</button>
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
export default {};
</script>
