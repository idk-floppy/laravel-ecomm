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
        //
    },
    mounted() {
        //
    },
    beforeUnmount() {
        //
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
