<template>
  <div class="page-container">
    <div class="form-container">
      <h2 class="mb-2">Create a new City</h2>
      <form>
        <div class="mb-3">
          <label for="inputCityName" class="form-label">Name of City</label>
          <input v-model="cityName" type="text" id="inputCityName" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="formFile" class="form-label">Photo of city</label>
          <input class="form-control" type="file" accept="image/*" id="formFile" @change="setCityImage($event)" required>
        </div>
        <div class="button-container">
          <button @click="addCityToDataBase" class="btn btn-primary addCity">Save</button>
          <button @click="redirectToCityList" class="btn btn-secondary">back</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import {ref} from "vue";
import axios from 'axios';

const cityName = ref(null);
const cityImage = ref(null);
const cityCoordinatesLat = ref(null);
const cityCoordinatesLon = ref(null);

function redirectToCityList(){
  window.location.assign(`/city/manager/`)
}

function setCityImage(e) {
  cityImage.value = e.target.files[0];
  console.log(cityImage.value)
}

async function addCityToDataBase(){
  if(cityName.value && cityImage.value){
    await getCityCoordinates();
    await sendCityDataToController();
  }
}

// Асинхронна функція для отримання координат міста, записую координати в const cityCoordinatesLat, const cityCoordinatesLon
async function getCityCoordinates() {
  try {
    // запит до API для отримання координат міста
    const response = await fetch(`https://geocode.maps.co/search?q=${cityName.value}+Poland&api_key=659450539ff1f762862410sea796255`)
    const data = await response.json();

    // Якщо результати є, беремо перший результат
    if (data.length > 0) {
      const cityCoordinates = data[0];
      cityCoordinatesLat.value = cityCoordinates.lat;
      cityCoordinatesLon.value = cityCoordinates.lon;
    } else {
      // Якщо результатів немає, вивести alert
      alert('City wasn`t added, enter a correct city name')
      cityName.value = null;
    }
  } catch (error) {
    // Обробка помилок, наприклад, у випадку невдалих запитів до API
    console.log('Error during fetching data from geocode.maps API:', error);
  }
}

async function sendCityDataToController(){
  if (cityName.value != null){
    try {
      let form = new FormData();
      form.set('cityName', cityName.value);
      form.set('cityPhoto', cityImage.value);
      form.set('cityCoordinatesLat', cityCoordinatesLat.value);
      form.set('cityCoordinatesLon', cityCoordinatesLon.value);
      await axios.post('/city/manager/add/city', form);
      alert(cityName.value + 'was added')
    }catch (error){
      console.log('Error with sending Data To Controller:', error);
    }
  }
}
</script>

<style scoped>
.page-container{
  display: flex;
  justify-content: center;
}

.form-container{
  margin-top: 4vh;
}

.button-container{
  display: flex;
}

.btn{
  margin-right: 20px;
}
</style>