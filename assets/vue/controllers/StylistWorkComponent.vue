<template>
  <div class="page-container">
    <div class="loader-container" v-if="stylistsWorksProps.stylistsWorksArray.length === 0">
      <div class="time-loader"></div>
    </div>
    <div class="service-data" v-else>
<!--      Фото робіт-->
<!--      <h2>Examples of {{ stylistName }}`s works</h2>-->
<!--      {% if services[0].getPhoto() %}-->
<!--      <div class="service-photo-container">-->
<!--        <img src="{{ asset(services[0].getPhoto()) }}" class="d-block service-image" style="height: 250px; width: 600px" alt="{{ services[0].getServiceName() }}" />-->
<!--      </div>-->
<!--      <div class="service-navigation">-->
<!--        <button class="btn btn-secondary" onclick="prevService()">Previous</button>-->
<!--        <button class="btn btn-secondary" style="float: right" onclick="nextService()">Next</button>-->
<!--      </div>-->
<!--      {% endif %}-->
      <template v-for="service in stylistsWorksProps.stylistsWorksArray" :key="service.id">
        <div class="service-container" v-if="serviceId === 0 || serviceId===service.serviceId">
  <!--           onmouseover="addAnimation(this)"-->
  <!--           onmouseout="removeAnimation(this)"-->
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
          <VueDatePicker v-model="date" :min-date="new Date()" :format="format" placeholder="Select date"></VueDatePicker>
        </div>
      </div>
      <div class="noneFreeHours" v-if="!ifFreeHours">
        <p>Chosen stylist has`t free hours on {{formatedDate}}, please choose another day</p>
      </div>
      <div class="free-hours-container" v-if="freeHours.length > 0">
        <h2>Choose Available Time</h2>
        <div class="free-hour" v-for="hour in freeHours" :key="hour.id">
          <button role="button" class="btn btn-secondary btn-choose-hour" @click="sendDataToReservation(hour)">
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
import {mapState}  from "vuex";
import {defineProps, onMounted, ref, watch,} from 'vue';
import axios from "axios";

const stylistsWorksProps = defineProps({
  stylistsWorksArray: Array,
});

const serviceId = ref(0)
const stylistId = ref(0)
const freeHours = ref([])
const date = ref(new Date());
const formatedDate = ref();
const format = (date) => {
  const day = date.getDate();
  const month = date.getMonth() + 1;
  const year = date.getFullYear();

  formatedDate.value = `${day}/${month}/${year}`
}
const waitingHoursArray = ref(false)
const ifFreeHours = ref(true);

function waitHours(){
  waitingHoursArray.value = true;
}

function  showDatePicker(service){
  const datePicker = document.querySelector('.datePicker-container');
  datePicker.classList.add('active');

  serviceId.value = service.serviceId;
  stylistId.value = service.stylistId;
}
////////////////////////////////////////////
async function getFreeHours() {
 try {
   freeHours.value = [];
   let form = new FormData();
   form.set('date', formatedDate.value)
   form.set('serviceId', serviceId.value)
   form.set('stylistId', stylistId.value)
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
////////////////////////////////////////////
async function sendDataToReservation(hour){
  try {
    let form = new FormData();
    form.set('stylistWorkId', serviceId.value);
    form.set('stylistId', stylistId.value);
    form.set('formatedDate', formatedDate.value);
    form.set('startTime', hour[0]);
    form.set('endTime', hour[1]);
    console.log(hour[0]);
    console.log(hour[1]);
    sendDataToController(await axios.post('/reservation', form));
  }catch (error){
    console.log('makeReservation error:', error)
  }
}
function sendDataToController(response){
  console.log(response.data.ifReservationAdded);
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