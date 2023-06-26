import './bootstrap';
import { createApp } from 'vue';
import ProductsGrid from './components/ProductsGrid.vue';
import ProductForm from './components/ProductForm.vue';
import Cart from './components/Cart.vue';
import NavItem from './components/NavItem.vue';
import Navbar from './components/Navbar.vue';
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

// import ErrorAlert from './swalAlerts';

const app = createApp({
    components: {
        'products-grid': ProductsGrid,
        'product-form': ProductForm,
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
        emitter.on('requestErrorPopup', this.flashError);
    },
    beforeUnmount() {
        emitter.off('requestErrorPopup', this.flashError);
    },
    provide() {
        return {
            user: this.user,
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
// app.directive('debounce', vue3Debounce({ lock: true, defaultTime: '400ms' }));
app.mount('#app');
