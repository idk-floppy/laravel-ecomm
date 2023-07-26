import './bootstrap';
import { createApp } from 'vue';
import ProductsGrid from './components/ProductsGrid.vue';
import ProductForm from './components/ProductForm.vue';
import ProductCard from './components/ProductCard.vue';
import Cart from './components/Cart.vue';
import NavItem from './components/NavItem.vue';
import Navbar from './components/Navbar.vue';
import LoadingOverlay from './components/LoadingOverlay.vue';
import mitt from 'mitt';

// import { vue3Debounce } from 'vue-debounce';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';

import axios from 'axios';
axios.defaults.baseURL = window.location.origin;

const emitter = mitt();
window.emitter = emitter;

const app = createApp({
    data() {
        //
    },
    created() {
        //
    },
    mounted() {
        emitter.on('requestErrorPopup', this.flashError);
    },
    beforeUnmount() {
        emitter.off('requestErrorPopup', this.flashError);
    },
    provide() {
        return {
            authenticated: window.authenticated,
        }
    },
    methods: {
        flashError() {
            return this.$swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
            });
        }
    }
});

app.config.globalProperties.emitter = emitter;

app.use(VueSweetalert2);
app.use(ToastPlugin, {
    position: 'top-right',
    duration: 3000,
});

app.component('loading-overlay', LoadingOverlay);
app.component('products-grid', ProductsGrid);
app.component('product-form', ProductForm);
app.component('product-card', ProductCard);
app.component('cart', Cart);
app.component('navitem', NavItem);
app.component('navbar', Navbar);

// app.directive('debounce', vue3Debounce({ lock: true, defaultTime: '400ms' }));
app.mount('#app');
