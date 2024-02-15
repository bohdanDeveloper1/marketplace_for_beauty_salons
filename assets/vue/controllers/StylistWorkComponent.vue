<template>
  <div class="page-container">
    <div class="loader-container" v-if="stylistsWorksArray.length === 0">
      <h3>Sorry, chosen stylist hav`nt available services</h3>
    </div>
    <div class="service-data" v-else>
      <template v-for="service in stylistsWorksArray" :key="service.id">
        <div class="service-container" v-if="serviceId === 0 || serviceId===service.serviceId">
            <div class="service-name">
              <h3>{{ service.serviceName }}</h3>
              <p> duration: {{ service.time }} minutes</p>
            </div>
            <div class="service-price">
              <h5>from {{ service.price }} PLN</h5>
              <button role="button" class="btn btn-primary" @click="showDatePicker(service)">Choose</button>
            </div>
        </div>
      </template>
      <div class="datepicker-container-main">
        <div class="datePicker-container">
          <VueDatePicker v-model="date" :min-date="new Date()" :format="'yyyy-MM-dd'" placeholder="Select date"></VueDatePicker>
        </div>
      </div>
      <div class="noneFreeHours" v-if="!ifFreeHours">
        <p>Chosen stylist has`t free hours on {{formatedDate}}, please choose another day</p>
      </div>
      <div class="free-hours-container" v-if="freeHours.length > 0">
        <h2 style="display: flex; justify-content: center; margin-top: 15px">Choose Available Time</h2>
        <div class="free-hour" v-for="hour in freeHours" :key="hour.id">
          <!--1    todo зберегти обрану годину та день (в storage, pinia)-->
          <!--2    todo висвітлити ConfirmationComponent перевірка чи година досі вільна після Book now -->
          <button role="button" class="btn btn-secondary btn-choose-hour" @click="setChosenHour(hour)">
            from {{hour[0]}}:00 to {{hour[1]}}:00
          </button>
        </div>
      </div>
      <div class="time-loader-container" v-if="waitingHoursArray && freeHours.length === 0">
        <div class="time-loader"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import {defineProps, ref, watch,} from 'vue';
import axios from "axios";

defineProps({
  stylistsWorksArray: Array,
});

const serviceId = ref(0)
const freeHours = ref([])
const date = ref(new Date());
const waitingHoursArray = ref(false)
const ifFreeHours = ref(true);

function waitHours(){
  waitingHoursArray.value = true;
}

function  showDatePicker(service){
  const datePicker = document.querySelector('.datePicker-container');
  datePicker.classList.add('active');

  serviceId.value = service.serviceId;
}

async function getFreeHours() {
 try {
   freeHours.value = [];
   let form = new FormData();
   let dateObject = new Date(date.value);
   let formattedDate = dateObject.toISOString().split('T')[0];
   form.set('date', formattedDate)
   form.set('serviceId', serviceId.value)
   getHours(await axios.post('/date/checker', form));
 }catch (error){
   console.log('await error:', error)
 }
}
function  getHours(response){
  freeHours.value = response.data.freeHoursResponse;
  if( response.data.freeHoursResponse.length === 0){
    ifFreeHours.value = false;
  }
}

function setChosenHour(hour){
  let dateObject = new Date(date.value);
  let formattedDate = dateObject.toISOString().split('T')[0];
  const encodedDate = encodeURIComponent(formattedDate);
  window.location.assign(`/confirmation/${serviceId.value}/${hour[0]}/${encodedDate}`);
}

watch(date, (newDate) =>{
  getFreeHours()
  waitHours()
})
</script>

<style scoped>
.datepicker-container-main{
  display: flex;
  justify-content: center;
}

.datePicker-container{
  width: 300px;
  display: none;
}

.active{
  display: block;
}

.page-container {
  display: flex;
  justify-content: center;
  margin-top: 50px;
}

.service-container {
  width: 600px;
  margin: 20px auto;
  /*auto - додає автоматичний відступ по горизонталі, розміщуючи контейнер в центрі сторінки.*/
  display: grid;
  grid-template-columns: 1fr 200px;
  align-items: center;
  gap: 20px;
  border: 1px solid #ccc;
  border-radius: 10px;
  padding: 15px;
  box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1);
}

.free-hour{
  display: flex;
  justify-content: center;
}

.btn-choose-hour{
  width: 200px;
  margin-top: 20px;
}

.service-name {
  text-align: left;
}

.service-price {
  text-align: right;
}
.grow:hover {
  -ms-transform: scale(1.1);
  transform: scale(1.1);
  transition-duration: 1s;
}
.service-image{
  border-radius: 30px;
  box-shadow: 2px 4px 24px -6px rgba(17, 17, 99, 1);
}


.loader-container{
  display: flex;
  justify-content: center;
  margin-top: 150px;
}

.time-loader-container{
  display: flex;
  justify-content: center;
  margin-top: 40px;
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