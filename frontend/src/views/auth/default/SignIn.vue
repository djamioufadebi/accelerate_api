<template>
  <section class="login-content">
    <b-row class="m-0 align-items-center bg-white h-100">
      <b-col md="6">
        <b-row class="justify-content-center">
          <b-col md="10">
            <b-card class="card-transparent shadow-none d-flex justify-content-center mb-0 auth-card iq-auth-form">
             
              <h2 class="mb-2 text-center">Connexion</h2>
              <p class="text-center">Identifiez-vous.</p>
              <form @submit.prevent="login">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="email" class="form-label">Email</label>
                      <input v-model="email" type="mail" class="form-control" id="email" aria-describedby="email" placeholder="Email " />
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="password" class="form-label">Mot de passe</label>
                      <input v-model="password" type="password" class="form-control" id="password" aria-describedby="password" placeholder="Mot de passe " />
                    </div>
                  </div>
                  <!-- <div class="col-lg-12 d-flex justify-content-between">
                    <div class="form-check mb-3">
                      <input type="checkbox" class="form-check-input" id="customCheck1" />
                      <label class="form-check-label" for="customCheck1">Remember Me</label>
                    </div>
                    <a href="/auth/reset-password">Mot de passe oublié?</a>
                  </div> -->
                </div>
                <span v-if="errorMessage" class="text-danger">{{ errorMessage.value }}</span>

                <div class="d-flex justify-content-center">
                  <button type="submit" class="btn btn-primary">Se connecter</button>
                </div>
                <!-- <p class="text-center my-3">Ou se connecter avec </p>
                <div class="d-flex justify-content-center">
                  <ul class="list-group list-group-horizontal list-group-flush">
                    <li class="list-group-item border-0 pb-0">
                      <a href="#"><img src="@/assets/images/brands/google.png" alt="gm" loading="lazy" /></a>
                    </li>
                  </ul>
                </div> -->
                <!-- <p class="mt-3 text-center">Pas encore un compte? <a href="/auth/register" class="text-underline">Créer un compte.</a></p> -->
              </form>
            </b-card>
          </b-col>
        </b-row>
 
        <div class="sign-bg">
          <svg width="280" height="230" viewBox="0 0 431 398" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g opacity="0.05">
              <rect x="-157.085" y="193.773" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 -157.085 193.773)" fill="#3B8AFF" />
              <rect x="7.46875" y="358.327" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 7.46875 358.327)" fill="#3B8AFF" />
              <rect x="61.9355" y="138.545" width="310.286" height="77.5714" rx="38.7857" transform="rotate(45 61.9355 138.545)" fill="#3B8AFF" />
              <rect x="62.3154" y="-190.173" width="543" height="77.5714" rx="38.7857" transform="rotate(45 62.3154 -190.173)" fill="#3B8AFF" />
            </g>
          </svg>
        </div>

      </b-col>


      <div class="col-md-6 d-md-block d-none bg-primary p-0 vh-100 overflow-hidden">
        <img src="@/assets/images/auth/01.png" class="img-fluid gradient-main animated-scaleX" alt="images" loading="lazy" />
      </div>

      
    </b-row>
  </section>
</template>

<script setup>
import {ref} from 'vue';
  // import axios from 'axios';
  import {useRouter} from 'vue-router';
  import {userAuthStore} from '@store/auth';


  const email = ref('');
  const password = ref('');
  const router = useRouter();
  const errorMessage = ref(null);

  const login = async () => {
    try {
    //   await axios.get('/sanctum/csrf-cookie');
    //   const response = await axios.post('http://localhost:8000/api/v1/login', {
    //     email: email.value,
    //     password: password.value
    //   });
    //   // console.log(response);
    // localStorage.setItem('token', response.data.token);
    // axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`

    await userAuthStore().login({
      email: email.value,
      password: password.value
    },router);

    } catch (error) {
      // error.value = 'Identifiants invalides !! '
      console.log(error);
      // errorMessage.value = error.response.data.message;  //pour afficher l'erreur depuis le serveur
      errorMessage.value = 'Identifiants invalides !! Veuillez rééssayer';
    }
  }

</script>

<style lang="scss" scoped></style>
