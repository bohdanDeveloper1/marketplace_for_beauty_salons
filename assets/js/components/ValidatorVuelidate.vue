<template>
  <div class='my-app'>
    <b-container>
      <b-form @submit="submit" @reset="reset">
        <b-form-group
            label="Adres email:"
            label-for="mail"
        >
          <b-form-input
              id="mail"
              type="email"
              v-model.trim="email"
              @input="$v.email.$model = $event"
              :state="!$v.email.$dirty ? null : !$v.email.$error"
              placeholder="Wpisz email"/>
          <b-form-invalid-feedback>
            <p v-if="!$v.email.required">email is required</p>
            <p v-if="!$v.email.email">incorrect email</p>
          </b-form-invalid-feedback>
          <b-form-valid-feedback>
            <p>correct email</p>
          </b-form-valid-feedback>
          <!--                {{$v.email}} - подивитись всі властивості $v.email-->
        </b-form-group>

        <b-form-group label="Nazwa użytkownika:" label-for="name">
          <b-form-input
              id="name"
              type="text"
              v-model="name"
              @input="$v.name.$model = $event"
              :state="!$v.name.$dirty ? null : !$v.name.$error"
              placeholder="Wpisz nazwę użytkownika" />
          <b-form-invalid-feedback>
            <p v-if="!$v.name.required">name is required</p>
            <p v-if="!$v.name.minLength">minLength of name is 5 symbols</p>
          </b-form-invalid-feedback>
          <b-form-valid-feedback>
            <p>correct name</p>
          </b-form-valid-feedback>
        </b-form-group>

        <b-form-group label="Hasło:" label-for="password">
          <b-form-input
              id="password"
              type="password"
              v-model="password"
              @input="$v.password.$model = $event"
              :state="!$v.password.$dirty ? null : !$v.password.$error"
              placeholder="Wpisz hasło" />
          <b-form-invalid-feedback>
            <p v-if="!$v.password.required">password is required</p>
            <p v-if="!$v.password.minLength">minLength of name is 5 symbols</p>
          </b-form-invalid-feedback>
          <b-form-valid-feedback>
            <p>correct password</p>
          </b-form-valid-feedback>
        </b-form-group>

        <b-button type="submit" variant="primary">Wyślij</b-button> &nbsp;
        <b-button type="reset" variant="danger">Resetuj</b-button>
      </b-form>
    </b-container>
  </div>
</template>

<script>
import {required, minLength, email } from 'vuelidate/lib/validators';

export default {
  name: "ValidatorVuelidate",

  data(){
    return {
      name: '',
      email: '',
      password: ''
    };
  },

  validations:{
    name:{
      required,
      minLength: minLength(5),
    },
    email:{
      required,
      email
    },
    password:{
      required,
      minLength: minLength(8)
    }
  },

  methods: {
    submit(event){
      event.preventDefault();
      // тронути кожен елемент форми
      this.$v.$touch();
      if (this.$v.$invalid){
        alert('data is invalid');
      }else {
        alert('data was send');
      }
    },
    reset(event){
      event.preventDefault();
      this.name = '';
      this.email = '';
      this.password = '';

      // скинути попередні зміни для $v
      this.$v.$reset();
    }
  }
}
</script>

<style scoped>
/*.my-app{*/
/*  padding: 50px 0;*/
/*}*/

/*!* Fade transition *!*/
/*.fade-enter-active, .fade-leave-active {*/
/*  transition: opacity .5s;*/
/*}*/
/*.fade-enter, .fade-leave-to {*/
/*  opacity: 0;*/
/*}*/
</style>