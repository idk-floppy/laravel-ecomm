import './bootstrap';
import { createApp } from 'vue';
import ProductsGrid from './components/ProductsGrid.vue';
import Cart from './components/Cart.vue';
import NavItem from './components/NavItem.vue';
import Navbar from './components/Navbar.vue';
import mitt from 'mitt';
import { vue3Debounce } from 'vue-debounce';

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
            user: window.authUser ?? null,
        }
    },
    created() {
        this.emitter.on('addToCart', this.addToCart);
    },
    mounted() {
        //
    },
    beforeUnmount() {
        this.emitter.off('addToCart', this.addToCart);
    },
    methods: {
        async addToCart(item, method = "add") {
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
    provide() {
        return {
            user: this.user,
        }
    }
});

app.config.globalProperties.emitter = emitter;

app.use(VueSweetalert2);
app.use(ToastPlugin, {
    position: 'top-right',
    duration: 3000,
});
app.directive('debounce', vue3Debounce({lock:true, defaultTime: '400ms'}));
app.mount('#app');
