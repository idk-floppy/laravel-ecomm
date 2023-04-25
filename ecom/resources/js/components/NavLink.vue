<template>
        <li class="nav-item">
            <a class="nav-link" :href="url"><slot></slot></a>
        </li>
</template>
<script>
export default {
    props: {
        url: {
            type: String,
            required: true,
        },
    },
}
</script>
