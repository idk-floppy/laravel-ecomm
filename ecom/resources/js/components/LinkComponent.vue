<template>
      <a :class="['nav-link', linkclass]" :href="url">
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
