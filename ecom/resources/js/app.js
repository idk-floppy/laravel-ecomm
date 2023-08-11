import './bootstrap';
import { createApp } from 'vue';
import { createStore } from 'vuex';
import ProductsGrid from './components/ProductsGrid.vue';
import ProductForm from './components/ProductForm.vue';
import ProductCard from './components/ProductCard.vue';
import CartTableView from './components/CartTableView.vue';
import NavItem from './components/NavItem.vue';
import LoadingOverlay from './components/LoadingOverlay.vue';
import ProductSingleSheet from './components/ProductSingleSheet.vue';
import LinkComponent from './components/LinkComponent.vue';
import MiniCartButton from './components/MiniCartButton.vue';
import MiniCart from './components/MiniCart.vue';
import NavItemDropdown from './components/NavItemDropdown.vue';
import mitt from 'mitt';
import { GetCartService } from './components/services/GetCartService';

// import { vue3Debounce } from 'vue-debounce';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';

import axios from 'axios';
axios.defaults.baseURL = window.location.origin;

const emitter = mitt();
window.emitter = emitter;

const store = createStore({
    state() {
        return {
            cartItemCount: 0,
            cartItems: [],
            isAuthenticated: false,
            cartTotal: 0,
        }
    },
    mutations: {
        setIsAuthenticated(state, status) {
            state.isAuthenticated = status;
        },
        setCartItems(state, items) {
            state.cartItems = items;
            state.cartItemCount = state.cartItems ? state.cartItems.length : 0;
        },
        setCartTotal(state, amount) {
            state.cartTotal = amount;
        }
    },
    actions: {
        async fetchIsAuthenticated({ commit }) {
            const response = await axios.get('auth/check');
            commit('setIsAuthenticated', response.data.isAuthenticated);
        },
        async fetchCartItems() {
            const cart = await GetCartService();
            this.dispatch('updateCartItems', cart)
        },
        updateCartItems({ commit }, cart) {
            commit('setCartTotal', cart.total);
            commit('setCartItems', cart.items);
        }
    },
    getters: {
        getIsAuthenticated: state => state.isAuthenticated,
        getCartTotal: state => state.cartTotal,
        getCartItemCount: state => state.cartItemCount,
        getCartItems: state => state.cartItems,
    }
});

const app = createApp({
    mounted() {
        emitter.on('requestErrorPopup', this.flashError);
        emitter.on('requestSuccessPopup', this.flashSuccess);
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
        flashError() {
            return this.$swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
            });
        },
        flashSuccess() {
            return this.$swal.fire({
                icon: 'success',
                title: 'Success',
            });
        },
        flashToast(icon = 'info', title = 'Example Toast Notification!') {
            return this.$swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                icon: icon,
                title: title,
            });
        }
    }
});

app.config.globalProperties.emitter = emitter;

app.use(store);
app.use(VueSweetalert2);
app.use(ToastPlugin, {
    position: 'top-right',
    duration: 3000,
});

app.component('loading-overlay', LoadingOverlay);
app.component('products-grid', ProductsGrid);
app.component('product-form', ProductForm);
app.component('product-card', ProductCard);
app.component('product-single-sheet', ProductSingleSheet);
app.component('cart-table', CartTableView);
app.component('mini-cart-button', MiniCartButton);
app.component('mini-cart', MiniCart);
app.component('navitem', NavItem);
app.component('custom-link', LinkComponent);
app.component('navitem-dropdown', NavItemDropdown);

// app.directive('debounce', vue3Debounce({ lock: true, defaultTime: '400ms' }));
app.mount('#app');
