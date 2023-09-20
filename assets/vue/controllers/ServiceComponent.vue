<template>
  <div class="service-container">
    <h1 style="text-align: center; margin-top: 50px">Our services</h1>
    <div class="card-container">
      <div class="show-services" v-for="service in servicesArray" :key="service.id">
        <div class="card" style="width: 14rem;">
          <img
              :src="service.photo"
              class="card-img-top"
              style="height: 200px; width: 200px"
              :alt="service.name"
          />
          <div class="card-body">
            <h5 class="card-title">{{ service.name }}</h5>
            <button role="button" class="btn btn-primary"  @click="$emit('chooseId', service.id)">Choose</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

 const idFromState =

defineProps({
  servicesArray: Array,
});
</script>


<style scoped>
.card-container{
  display: flex;
  justify-content: center;
}

.card{
  margin-right: 25px;
}
</style>
