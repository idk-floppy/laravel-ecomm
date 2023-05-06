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
window.mitt = emitter;

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
            cartItems: [],
        };
    },
    created() {
        this.emitter.on('buy', this.buy);
        this.emitter.on('clearCart', this.clearCart);
    },
    mounted() {
        console.log(this.cartItems);
    },
    beforeUnmount() {
        this.emitter.off('buy', this.buy);
    },
    methods: {
        async buy(item) {
            await axios.post(
                'cart/add',
                {
                    product_data: JSON.stringify(item),
                }).then(
                    response => {
                        console.log(response);
                        toastr.success(item.name + ' added to cart!', 'Success');
                    }).catch(error => {
                        console.log(error);
                        toastr.error('Something went wrong!', 'Error');
                    });
        },
        removeItem(index) {
            toastr.success('Item removed from cart', 'Success');
        },
        clearCart() {
            Swal.fire({
                title: 'Do you want to empty your cart?',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.cartItems = [];
                    Swal.fire('Cart deleted!', '', 'success').then(() => {
                        window.location.href = "/";
                    });
                }
            })
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
