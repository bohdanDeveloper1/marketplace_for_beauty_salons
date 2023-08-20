<template>
  <div>
    <h1 class="title">{{ message }}</h1>
  </div>
</template>

<script>
export default {
  name: "TestComponent",
  data() {
    return {
      message: 'Hello from Vue component!'
    };
  }
}
</script>

<style scoped>

</style>