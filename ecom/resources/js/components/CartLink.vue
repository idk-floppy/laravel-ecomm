<template>
    <navitem :url="url">Cart ({{ $parent.cartItemsCount }})</navitem>
</template>
<script>
import NavItem from './NavItem.vue';
export default {
    components: {
        'navitem': NavItem,
    },
    props: {
        url: {
            type: String,
            required: true,
        },
    },
}
</script>
