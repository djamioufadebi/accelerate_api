<template>
  <section class="login-content">
    <div class="row m-0 align-items-center bg-white vh-100">
      <div class="col-md-6 d-md-block d-none bg-primary p-0 vh-100 overflow-hidden">
        <img src="@/assets/images/auth/05.png" class="img-fluid gradient-main animated-scaleX" alt="images" loading="lazy" />
      </div>


      <div class="col-md-6">
        <div class="row justify-content-center">
          <div class="col-md-10">
            <div class="card card-transparent auth-card shadow-none d-flex justify-content-center mb-0">
              <div class="card-body">

                <h2 class="mb-2 text-center">S'inscrire</h2>
                <p class="text-center">Créer votre compte.</p>
                <form>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="full-name" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="full-name" placeholder=" " />
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="last-name" class="form-label">Prénoms</label>
                        <input type="text" class="form-control" id="last-name" placeholder=" " />
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder=" " />
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="phone" class="form-label">Téléphone.</label>
                        <input type="text" class="form-control" id="phone" placeholder=" " />
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="password" class="form-label">Mot de passe </label>
                        <input type="password" class="form-control" id="password" placeholder=" " />
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="confirm-password" class="form-label">Confirmer mot de passe</label>
                        <input type="text" class="form-control" id="confirm-password" placeholder=" " />
                      </div>
                    </div>
                    <div class="col-lg-12 d-flex justify-content-center">
                      <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="customCheck1" />
                        <label class="form-check-label" for="customCheck1">J'accepte les conditions d'utilisation</label>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">S'inscrire</button>
                  </div>
                  <p class="text-center my-3">Ou s'inscrire avec </p>
                  <div class="d-flex justify-content-center">
                    <ul class="list-group list-group-horizontal list-group-flush">
                      <li class="list-group-item border-0 pb-0">
                        <a href="#"><img src="@/assets/images/brands/google.png" alt="gm" loading="lazy" /></a>
                      </li>
                    </ul>  
                  </div>
                  <p class="mt-3 text-center">Déjà inscrit ? <a href="/auth/login" class="text-underline">Se connecter</a></p>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="sign-bg sign-bg-right">
          <svg width="280" height="230" viewBox="0 0 421 359" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g opacity="0.05">
              <rect x="-15.0845" y="154.773" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 -15.0845 154.773)" fill="#3A57E8" />
              <rect x="149.47" y="319.328" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 149.47 319.328)" fill="#3A57E8" />
              <rect x="203.936" y="99.543" width="310.286" height="77.5714" rx="38.7857" transform="rotate(45 203.936 99.543)" fill="#3A57E8" />
              <rect x="204.316" y="-229.172" width="543" height="77.5714" rx="38.7857" transform="rotate(45 204.316 -229.172)" fill="#3A57E8" />
            </g>
          </svg>
        </div>
      </div>
      
    </div>
  </section>
</template>

<script setup></script>

<style lang="scss" scoped></style>
