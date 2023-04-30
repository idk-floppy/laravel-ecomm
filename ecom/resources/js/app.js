import './bootstrap';
import { createApp } from 'vue';
import ProductsGrid from './components/ProductsGrid.vue';
import Cart from './components/Cart.vue';
import NavLink from './components/NavLink.vue';
import Navbar from './components/Navbar.vue';
import mitt from 'mitt';
import { store } from './store';

const emitter = mitt();
emitter.on('buy', e => {
    console.log("buy triggered, got in appjs");
});

const app = createApp({
    components: {
        'products-grid': ProductsGrid,
        'cart': Cart,
        'navlink': NavLink,
        'navbar': Navbar
    },
});

app.config.globalProperties.emitter = emitter;

app.mount('#app');
