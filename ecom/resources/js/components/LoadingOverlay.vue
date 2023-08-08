<template>
    <div :class="['spinner-container', overlay]">
      <div class="spinner-border" role="status"></div>
    </div>
</template>
<script>
export default {
    props:{
        overlay: {
            type: String,
            default: '',
            validator: (val)=>{
                const options = ["fullscreen", ""];
                return options.includes(val);
            },
        }
    }
}
</script>
<style scoped>
.spinner-container {
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.25);
}
.fullscreen {
  position: fixed;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
