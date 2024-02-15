<template>
  <div class="page-container">
    <div class="content" v-if="ifServiceDataLoaded">
      <div class="main-info">
        <h3 style="margin-bottom: 10px">Confirm your reservation</h3>
        <p>{{ props.chosenServiceData.serviceName }}</p>
        <p>Price: {{ props.chosenServiceData.price }} PLN</p>
      </div>
      <div class="reservation-info">
        <p>Stylist: {{ props.chosenServiceData.stylistName}}</p>
        <p>Visiting time: {{ props.dateData.startTime }}:00 on {{ props.dateData.date }}</p>
        <p>Service duration: from {{ props.dateData.startTime }}:00 to {{ props.dateData.endTime }}:00</p>
      </div>
      <div class="accepting-info" v-if="acceptReservation">
        <div class="accepting-info-container">
          <h4>Reservation was accepted</h4>
           <p>We have send confirmation on your email. </p>
           <p>If you didn't receive an email, please check spam.</p>
          <button role="button" class="btn btn-secondary btn-to-services" @click="redirectToServices">Go to salons</button>
        </div>
      </div>
      <div class="confirmationByUser" v-if="!acceptReservation && !ifSelectedHourWasReserved">
        <button @click="makeReservation" role="button" class="btn btn-primary">Book now</button>
      </div>
      <div class="selectedHourWasReserved" v-if="ifSelectedHourWasReserved">
        <b>Sorry, hour that you chose was reserved by another client</b>
        <p>Please choose another hour</p>
        <button role="button" class="btn btn-primary" @click="redirectToChooseAnotherHour">Choose</button>
      </div>
    </div>
    <div class="loader-container" v-else>
      <div class="loader"></div>
    </div>
  </div>
</template>

<script setup>
import {ref} from "vue";
import axios from "axios";

const props = defineProps({
  chosenServiceData: Array,
  dateData: Array,
  userEmail: String
})

// видалити ifServiceDataLoaded
const ifServiceDataLoaded = ref(true);
const ifSelectedHourWasReserved = ref(false)
const acceptReservation = ref(false);

//перевірка, чи місце досі вільне, якщо так то висилка мейлу + резервація
async function makeReservation(){
  try{
    let form  = new FormData();
    form.set('date', props.dateData.date);
    form.set('serviceId', props.chosenServiceData.serviceId);
    form.set('chosenHourStart', props.dateData.startTime);
    checkIfFree(await axios.post('/date/checker/confirmation', form ))
  }catch(error){
    console.log('checkChosenTime error:', error)
  }
}

function checkIfFree(response){
  if(response.data.ifChosenHourInFreeHours){
    //висилка мейлу + запис до бази данних
    sendDataToReservation();
    sendEmail();
    acceptReservation.value = true;
  }else{
    ifSelectedHourWasReserved.value = true;
  }
  console.log('ifSelectedHourWasReserved', ifSelectedHourWasReserved.value);
}

function sendEmail(){
  Email.send({
    Host : "smtp.elasticemail.com",
    Username : "studentAP123@gmail.com",
    Password : "049EEFA46C18C343B108C44D2B2A6A09D86F",
    To : `${props.userEmail}`,
    From : "studentAP123@gmail.com",
    Subject : "Thanks for reservation in our salon",
    Body : `We are waiting for you on ${props.dateData.date}, at ${props.dateData.startTime}. <br>
            Your service name: ${props.chosenServiceData.serviceName}, price: ${props.chosenServiceData.price}. Stylist name is ${props.chosenServiceData.stylistName}.`
  })
}

async function sendDataToReservation(){
  try {
    let form = new FormData();
    form.set('stylistWorkId', props.chosenServiceData.serviceId);
    form.set('formatedDate', props.dateData.date);
    form.set('startTime', props.dateData.startTime);
    form.set('endTime', props.dateData.endTime);
    sendDataToController(await axios.post('/reservation', form));
  }catch (error){
    console.log('makeReservation error:', error);
  }
}
function sendDataToController(response){
  console.log('ifReservationAdded: ', response.data.ifReservationAdded);
}

function redirectToServices(){
  window.location.assign(`/choose/city`)
}
function redirectToChooseAnotherHour(){
  window.location.assign(`/stylist/works/${props.chosenServiceData.stylistId}`)
}
</script>

<style scoped>
.page-container{
  display: flex;
  justify-content: center;
  margin-top: 40px;
}
.accepting-info{
  display: flex;
  justify-content: center;
  margin-top: 40px;
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