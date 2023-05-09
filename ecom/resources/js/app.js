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

import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';

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
        async buy(item, method = "add") {
            await axios.post(
                'cart/add',
                {
                    product_data: JSON.stringify(item),
                    method: method,
                }).then(
                    response => {
                        console.log(response.data.success);
                        if (response.data.success) {
                            this.$toast.success(item.name+" added to cart");
                        } else {
                            this.$toast.error("Something went wrong!");
                        }
                    }).catch(error => {
                        this.$toast.error("Something went wrong!");
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
app.use(ToastPlugin, {
    position: 'top-right',
    duration: 3000,
});
app.mount('#app');
