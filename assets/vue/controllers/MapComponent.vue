<template>
  <div class="map-wrapper">
    <div id="map"></div>
  </div>
</template>

<script setup>
import { defineProps, onMounted } from 'vue';
import marker from '../../images/redMarkerForMap.png';

let props = defineProps({
  currentCityCoordinates: String,
  salonsInCurrentCityData: String,
});
let salons = [];
let currentCityCoordinates = [];
let map;

// відображаю місто на карті
async function addCityToMap(){
  try {
    addMap();
  }catch (error){
    document.getElementById("map").innerHTML = 'Some error with maps, please go to console';
    console.error('Error:', error);
  }
}

function addMap(){
  // Створення карти з використанням отриманих координат
  map = L.map('map').setView([currentCityCoordinates[0], currentCityCoordinates[1]], 13);

  // Додавання шару з тайлами OpenStreetMap до карти
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  }).addTo(map);
}

async function getSalonsCoordinates() {
  try {
    for(let salon of salons){
      L.marker([salon.coordinatesLat, salon.coordinatesLon]).addTo(map).bindPopup(
          `<b>${salon.name}</b>
           <p>address: ${salon.adress}</p>
           <p>${salon.description}</p>`
      );
    }
  } catch (error) {
    // Обробка помилок, наприклад, у випадку невдалих запитів до API
    console.log('Error with function getSalonsCoordinates:', error);
  }
}

function addUserMark(){
  // Створення червоного маркера
  let redMarker = L.icon({
    iconUrl: marker, // Зображення для червоного маркера
    iconSize: [32, 32], // Розмір значка
    iconAnchor: [16, 32], // Точка прив'язки значка
  });

  const successCallback = (position) => {
    L.marker([position.coords.latitude, position.coords.longitude], {icon: redMarker}).addTo(map).bindPopup('Your location');
  };
  const errorCallback = (error) => {
    console.log(error);
  };
  navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
}

// додаю місто на карту, потім додаю користувача на карту, потім додаю всі салони міста на карту
onMounted(() => {
  try {
    salons = JSON.parse(props.salonsInCurrentCityData);
    currentCityCoordinates = JSON.parse(props.currentCityCoordinates);
    addCityToMap().then(() => {
      addUserMark();
      getSalonsCoordinates();
    });
  } catch (error) {
    console.error('Error parsing salons data:', error);
  }
});
</script>

<style scoped>
.map-wrapper{
  display: flex;
  justify-content: center;
}
#map {
  height: 50vh;
  width: 94%;
}
</style>
<!--//  отримати поточне місце розташування користувача і поставити там мітку.-->
<!--після вибору салону користувачем, має з'явитись інформація про салон-->

