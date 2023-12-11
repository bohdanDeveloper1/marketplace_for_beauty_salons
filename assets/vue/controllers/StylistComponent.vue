<template>
<div class="page-container">
  <div class="ifStylistInStylistsArray" v-if="stylistsArray.length === 0">
    <h3 class="noneStylists">Sorry, our salon has`t stylist for your service</h3>
  </div>
  <div class="stylist-container row" v-else>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12" v-for="stylist in stylistsArray" :key="stylist.id">
     <div class="card">
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
</style>









