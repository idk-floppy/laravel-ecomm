<template>
    <div class="dropdown-center" @click="handleDropdownClick">
        <a class="btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <slot name="text">text</slot>
        </a>

        <ul class="dropdown-menu">
            <slot name="items"></slot>
        </ul>
    </div>
</template>
<script>
export default {
    methods: {
        handleDropdownClick(event) {
            event.stopPropagation();
        }
    }
}
</script>
<style scoped></style>
