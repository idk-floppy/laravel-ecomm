<template>
    <a :class="['link-underline link-underline-opacity-0', linkclass]" :href="url">
        <slot>Link</slot>
    </a>
</template>
<script>
export default {
    props: {
        url: {
            type: String,
            default: "#",
        },
        linkclass: {
            type: String,
            default: "",
        }
    },
};
</script>
