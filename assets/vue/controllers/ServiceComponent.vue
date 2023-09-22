<template>
 <div class="page-container">
   <div class="service-container">
     <h1 style="text-align: center; margin-top: 50px">Our services</h1>
     <div class="loader-container" v-if="servicesArray.length === 0">
       <div class="loader"></div>
     </div>
     <div class="cards-container" v-else>
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
             <button role="button" class="btn btn-primary"  @click="setService(service.id)">Choose</button>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>
</template>

<script setup>
import { defineProps } from 'vue';

defineProps({
  servicesArray: Array,
});

function setService(id){
  window.location.assign(`/stylist/${id}`);
}

</script>



<style scoped>
.cards-container{
  display: flex;
  justify-content: center;
}

.card{
  margin-right: 25px;
}


.loader-container{
  display: flex;
  justify-content: center;
  margin-top: 150px;
}

.loader {
  width: fit-content;
  font-weight: bold;
  font-family: monospace;
  font-size: 30px;
  background: linear-gradient(90deg,#000 50%,#0000 0) right/200% 100%;
  animation: l21 2s infinite linear;
}

.loader::before {
  content: "Loading...";
  color: #0000;
  padding: 0 5px;
  background: inherit;
  background-image: linear-gradient(90deg,#fff 50%,#000 0);
  -webkit-background-clip: text;
  background-clip: text;
}

@keyframes l21 {
  100% {
    background-position: left
  }
}
</style>



