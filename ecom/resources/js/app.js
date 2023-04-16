import './bootstrap';
import { createApp } from 'vue';
const app = createApp({});

import ProductsGrid from './components/ProductsGrid.vue';
app.component('products-grid', ProductsGrid);

app.mount('#app');
