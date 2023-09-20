<template>
<div class="page-container">
  <div class="ifStylistInStylistsArray" v-if="serviceData.ifStylistInStylistsArray === false">
    <h3 class="noneStylists">Sorry, our salon has`t stylist for your service</h3>
  </div>
  <div class="loader-container" v-else-if="serviceData.stylistsArray.length === 0">
      <div class="time-loader"></div>
  </div>
  <div class="stylist-container" v-else>
    <div class="card" style="width: 18rem;"  v-for="stylist in serviceData.stylistsArray" :key="stylist.id">
      <img
          :src="stylist.photo"
          class="card-img-top"
          :alt="stylist.name">
      <div class="card-body">
        <p>{{stylist.instagram}}</p>
        <h5 class="card-title">{{stylist.name}}</h5>
        <p class="card-text">{{stylist.description}}</p>
        <button role="button" class="btn btn-primary" @click="getStylists(stylist.id)">Choose</button>
      </div>
    </div>
  </div>
</div>
  serviceInfoLocal - {{serviceIdLocal}} <br><br>
  <hr>
  serviceId - {{serviceData.serviceId}}
  <hr>
</template>

<script setup>
import {defineProps, reactive, ref, toRefs } from "vue";

const serviceData = defineProps({
  serviceId: Number,
  stylistsArray: Array,
  ifStylistInStylistsArray: Boolean,
  serviceInfo: Object
})




let serviceInfoLocal = reactive(serviceData.serviceInfo)
let serviceIdLocal = ref (serviceData.serviceId )
let stylistsArrayLocal = reactive (serviceData.stylistsArray)
let ifStylistInStylistsArrayLocal = ref (serviceData.ifStylistInStylistsArray)

async function getStylists(id){
  try{
    serviceIdLocal.value = id
    alert(serviceIdLocal.value)
    serviceInfoLocal.serviceId  = id
    setStylists(await axios.get(`/stylist/${serviceIdLocal.value}`));
  }catch (error){
    console.log(error)
  }
}
function setStylists(response){
  stylistsArrayLocal = response.data.stylistsArray;
  if( stylistsArrayLocal.length === 0){
    ifStylistInStylistsArrayLocal.value = false;
  }
}
</script>

<style scoped>
.stylist-container{
  display: flex;
  justify-content: center;
  margin-top: 50px;
}

.card{
  margin-right: 25px;
}

.noneStylists{
  display: flex;
  justify-content: center;
  margin-top: 150px;
}

.loader-container{
  display: flex;
  justify-content: center;
  margin-top: 150px;
}

.time-loader {
  width: fit-content;
  font-weight: bold;
  font-family: monospace;
  font-size: 30px;
  background: linear-gradient(90deg,#000 50%,#0000 0) right/200% 100%;
  animation: l21 2s infinite linear;
}

.time-loader::before {
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









