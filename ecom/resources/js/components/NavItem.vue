<template>
  <li class="nav-item">
    <a :class="['nav-link', linkclass]" :href="url">
      <slot>Link</slot>
    </a>
  </li>
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
