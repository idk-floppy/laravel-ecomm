import './bootstrap';
import { createApp } from 'vue';
import ProductsGrid from './components/ProductsGrid.vue';
import Cart from './components/Cart.vue';
import NavLink from './components/NavLink.vue';
import Navbar from './components/Navbar.vue';
import NavItem from './components/NavItem.vue';
import CartLink from './components/CartLink.vue';
import mitt from 'mitt';

import Swal from 'sweetalert2';
import toastr from 'toastr';
import 'toastr/build/toastr.css'
toastr.options = {
    closeButton: true,
    progressBar: true, // enable progress bar
    positionClass: 'toast-top-right',
    timeOut: 3000,
    extendedTimeOut: 2000,
};

const emitter = mitt();
emitter.on('buy', e => {
    console.log("buy triggered, got in appjs");
});

const app = createApp({
    components: {
        'products-grid': ProductsGrid,
        'cart': Cart,
        'navlink': NavLink,
        'navbar': Navbar,
        'navitem': NavItem,
        'cartlink': CartLink,
    },
    data() {
        return {
            cartItems: JSON.parse(localStorage.getItem('cart')) || [],
        };
    },
    created() {
        this.emitter.on('buy', this.buy);
        window.addEventListener('beforeunload', this.saveCartToLocalStorage);
    },
    beforeUnmount() {
        this.emitter.off('buy', this.buy);
        window.removeEventListener('beforeunload', this.saveCartToLocalStorage);
        this.saveCartToLocalStorage();
    },
    methods: {
        buy(item) {
            this.cartItems.push(item);
            toastr.success(item.name + ' added to cart!', 'Success');
        },
        removeItem(index) {
            this.cartItems.splice(index, 1);
            toastr.success('Item removed from cart', 'Success');
        },
        clearCart() {
            this.cartItems = [];
            Swal.fire(
                'Success!',
                'Cart cleared!',
                'success'
            ).then(() => {
                window.location.href = "/";
            });
        },
        saveCartToLocalStorage() {
            localStorage.setItem('cart', JSON.stringify(this.cartItems));
        },
    },
    computed: {
        cartItemsCount() {
            return this.cartItems.length;
        },
        cartTotalPrice() {
            return this.cartItems.reduce((total, item) => total + item.price, 0);
        },
    },
});

app.config.globalProperties.emitter = emitter;

app.mount('#app');
