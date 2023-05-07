import './bootstrap';
import { createApp, defineAsyncComponent } from 'vue';
import ProductsGrid from './components/ProductsGrid.vue';
// import Cart from './components/Cart.vue';
const Cart = defineAsyncComponent(() => import('./components/Cart.vue'));
import NavItem from './components/NavItem.vue';
import Navbar from './components/Navbar.vue';
import mitt from 'mitt';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import toastr from 'toastr';
import 'toastr/build/toastr.css'
toastr.options = {
    closeButton: true,
    progressBar: true,
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
        'navitem': NavItem,
        'navbar': Navbar,
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
                        console.log(response.data.success);
                        if (response.data.success) {
                            toastr.success(item.name + ' added to cart!', 'Success');
                        } else {
                            toastr.error('Something went wrong!', 'Failure');
                        }
                    }).catch(error => {
                        console.log(error);
                        toastr.error('Something went wrong!', 'Error');
                    });
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

app.use(VueSweetalert2);
app.mount('#app');
