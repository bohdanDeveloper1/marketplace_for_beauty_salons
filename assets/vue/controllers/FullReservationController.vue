<template>
  <div class="page-container">
    <div class="services" v-if="serviceId === 0">
      <ServiceComponent :services-array="servicesArray" @choose-id="(id) => getStylists(id)" />
    </div>
    <div class="stylists" v-if="serviceId !== 0 && stylistId === 0">
      <StylistComponent :service-id="serviceId" :serviceInfo="serviceInfo" :stylists-array="stylistsArray"
      :if-stylist-in-stylists-array="ifStylistInStylistsArray"/>
    </div>
    <div class="stylist-works" v-if="stylistId !== 0">
      <StylistWorkComponent :stylists-works-array="stylistsWorksArray" />
    </div>
    <div class="confirmationOfReservation">
      <ConfirmationOfReservation/>
    </div>
  </div>
  serviceInfo - {{serviceInfo}}<br>

</template>

<script setup>
import ServiceComponent from "./ServiceComponent.vue";
import StylistWorkComponent from "./StylistWorkComponent.vue";
import FreeHoursComponent from "./FreeHoursComponent.vue";
import ReservationDetailsComponent from "./ReservationDetailsComponent.vue";
import StylistComponent from "./StylistComponent.vue";
import ConfirmationOfReservation from "./confirmationOfReservation.vue";
import axios from "axios";
import {ref, watch} from "vue";
import {recommendation} from "@symfony/webpack-encore/lib/logger";

defineProps({
  servicesArray: Array
})


const serviceInfo = {'serviceId': 0, 'stylistId': 0}
const serviceId = ref(0);
const stylistId = ref(0);
const stylistsArray = ref([]);
const ifStylistInStylistsArray = ref(true);
const stylistsWorksArray = ref([]);

async function getStylists(id){
  try{
    serviceId.value = id
    setStylists(await axios.get(`/stylist/${serviceId.value}`));
  }catch (error){
    console.log(error)
  }
}
function setStylists(response){
  stylistsArray.value = response.data.stylistsArray;
  if( stylistsArray.value.length === 0){
    ifStylistInStylistsArray.value = false;
  }
}
////////////////////////////////////////////////////////
async function getStylistWorks(id){
  try{
    stylistId.value = id
    setStylistWorks(await axios.get(`/stylist/works/${stylistId.value}`));
  }catch (error){
    console.log(error)
  }
}
function setStylistWorks(response){
  stylistsWorksArray.value = response.data.servicesArray;
}
////////////////////////////////////////////////////////




</script>

<style scoped>

</style>