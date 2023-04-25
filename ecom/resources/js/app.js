import './bootstrap';
import { createApp } from 'vue';
import ProductsGrid from './components/ProductsGrid.vue';
import Cart from './components/Cart.vue';
import NavLink from './components/NavLink.vue';
import Navbar from './components/Navbar.vue';

const app = createApp({
  components: {
    'products-grid': ProductsGrid,
    'cart': Cart,
    'navlink': NavLink,
    'navbar': Navbar
  }
});

app.mount('#app');
