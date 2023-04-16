import './bootstrap';
import { createApp } from 'vue';
const app = createApp({});

import ProductsGrid from './components/ProductsGrid.vue';
app.component('product-grid', ProductsGrid);

app.mount('#app');
