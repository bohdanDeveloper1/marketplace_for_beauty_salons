<template>
<div class="page-container">
  <div class="ifStylistInStylistsArray" v-if="stylistsArray.length === 0">
    <h3 class="noneStylists">Sorry, our salon has`t stylist for your service</h3>
  </div>
  <div class="stylists-container" v-else>
    <div class="card" v-for="stylist in stylistsArray" :key="stylist.id">
       <img
           :src="'/'+stylist.photo"
           class="card-img-top"
           :alt="stylist.name"
       />
       <div class="card-body">
         <p>{{stylist.instagram}}</p>
         <h5 class="card-title">{{stylist.name}}</h5>
         <p class="card-text">{{stylist.description}}</p>
         <button role="button" class="btn btn-primary" @click="setStylist(stylist.id)">Choose</button>
       </div>
    </div>
  </div>
</div>
</template>

<script setup>
import {defineProps, ref} from "vue";

defineProps({
  stylistsArray: Array,
});

function setStylist(id){
  window.location.assign(`/stylist/works/${id}`);
}

</script>

<style scoped>
.stylists-container{
  margin-top: 24px;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-evenly;
}

.card{
  width: 350px;
  margin: 0 12px 20px 12px;
  border: 2px solid lightgray;
}

.noneStylists{
  display: flex;
  justify-content: center;
  margin-top: 150px;
}

.card-img-top{
  width: 100%;
  height: 250px;
}
</style>









