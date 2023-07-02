<template>
  <div class="card">
    <slot name="image" class="card-img-top"></slot>
    <div class="card-body">
      <h5 class="card-title"><slot name="title"></slot></h5>
      <h6 class="card-subtitle mb-2 text-muted">
        <slot name="details"></slot>
      </h6>
      <div class="row row-cols-1 g-2">
        <slot name="buttons"></slot>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  //
};
</script>
<style scoped>
.card-img-top {
  object-fit: cover;
  object-position: center;
  height: clamp(300px, 350px, 450px);
}
</style>
