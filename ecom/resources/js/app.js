import './bootstrap';
import { createApp } from 'vue';
import { createStore } from 'vuex';
import ProductsGrid from './components/ProductsGrid.vue';
import ProductForm from './components/ProductForm.vue';
import ProductCard from './components/ProductCard.vue';
import Cart from './components/Cart.vue';
import NavItem from './components/NavItem.vue';
import Navbar from './components/Navbar.vue';
import LoadingOverlay from './components/LoadingOverlay.vue';
import ProductSingleSheet from './components/ProductSingleSheet.vue';
import LinkComponent from './components/LinkComponent.vue';
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

const store = createStore({
    state() {
        return {
            count: 0,
            cartItems: [],
            isAuthenticated: false,
        }
    },
    mutations: {
        setIsAuthenticated(state, status) {
            state.isAuthenticated = status;
        },
    },
    actions: {
        async fetchIsAuthenticated({ commit }) {
            const response = await axios.get('auth/check');
            commit('setIsAuthenticated', response.data.isAuthenticated);
        }
    },
    getters: {
        getIsAuthenticated: state => state.isAuthenticated,
    }
});

const app = createApp({
    async mounted() {
        emitter.on('requestErrorPopup', this.flashError);
        await this.$store.dispatch('fetchIsAuthenticated');
    },
    beforeUnmount() {
        emitter.off('requestErrorPopup', this.flashError);
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
app.component('cart', Cart);
app.component('navitem', NavItem);
app.component('link', LinkComponent);
app.component('navbar', Navbar);

// app.directive('debounce', vue3Debounce({ lock: true, defaultTime: '400ms' }));
app.mount('#app');
