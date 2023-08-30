import './bootstrap';
import { createApp } from 'vue';
import ProductsGrid from './components/ProductsGrid.vue';
import ProductForm from './components/ProductForm.vue';
import ProductCard from './components/ProductCard.vue';
import CartTableView from './components/CartTableView.vue';
import CartListView from './components/CartListView.vue';
import NavItem from './components/NavItem.vue';
import LoadingOverlay from './components/LoadingOverlay.vue';
import ProductSingleSheet from './components/ProductSingleSheet.vue';
import LinkComponent from './components/LinkComponent.vue';
import MiniCartButton from './components/MiniCartButton.vue';
import MiniCart from './components/MiniCart.vue';
import NavItemDropdown from './components/NavItemDropdown.vue';
import mitt from 'mitt';
import { handleCallback } from './components/services/HelperFunctions';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
// import '@sweetalert2/theme-borderless/borderless.scss'; // ez jól néz ki, csak a toast néz ki hülyén

import axios from 'axios';
axios.defaults.baseURL = window.location.origin;

const emitter = mitt();
window.emitter = emitter;

const app = createApp({
    mounted() {
        emitter.on('requestErrorPopup', (e) => {
            this.flashError(e.message, e.callback);
        });
        emitter.on('requestSuccessPopup', (e) => {
            this.flashSuccess(e.message, e.callback);
        });
        emitter.on('flashToast', (e) => {
            this.flashToast(e.icon, e.title);
        });
        this.$store.dispatch('fetchCartItems');
        this.$store.dispatch('fetchIsAuthenticated');
    },
    beforeUnmount() {
        emitter.off('requestErrorPopup', this.flashError);
        emitter.off('requestSuccessPopup', this.flashSuccess);
        emitter.off('flashToast', this.flashToast);
    },
    methods: {
        flashError(message = "Something went wrong!", callback = null) {
            const swalResponse = this.$swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
            });

            handleCallback(swalResponse, callback);
        },
        flashSuccess(message = null, callback = null) {
            const swalResponse = this.$swal.fire({
                icon: 'success',
                title: 'Success',
                text: message,
            });

            handleCallback(swalResponse, callback);
        },
        flashToast(icon = 'info', title = 'Example Toast Notification!') {
            return this.$swal.fire({
                toast: true,
                position: 'center',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                icon: icon,
                title: title,
            });
        }
    }
});

app.config.globalProperties.emitter = emitter;

import { store } from './store';
app.use(store);
app.use(VueSweetalert2);

app.component('loading-overlay', LoadingOverlay);
app.component('products-grid', ProductsGrid);
app.component('product-form', ProductForm);
app.component('product-card', ProductCard);
app.component('product-single-sheet', ProductSingleSheet);
app.component('cart-table', CartTableView);
app.component('cart-list', CartListView);
app.component('mini-cart-button', MiniCartButton);
app.component('mini-cart', MiniCart);
app.component('navitem', NavItem);
app.component('custom-link', LinkComponent);
app.component('navitem-dropdown', NavItemDropdown);

// app.directive('debounce', vue3Debounce({ lock: true, defaultTime: '400ms' }));
app.mount('#app');
