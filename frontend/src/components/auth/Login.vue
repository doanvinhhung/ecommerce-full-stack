<template>
  <section id="wsus__login_register">
    <div class="container">
      <div class="row">
        <div class="col-xl-5 m-auto">
          <div class="wsus__login_reg_area">
            <ul class="nav nav-pills mb-3" id="pills-tab2" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-home-tab2" data-bs-toggle="pill"
                  data-bs-target="#pills-homes" type="button" role="tab" aria-controls="pills-homes"
                  aria-selected="true">Login</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-profile-tab2" data-bs-toggle="pill"
                  data-bs-target="#pills-profiles" type="button" role="tab"
                  aria-controls="pills-profiles" aria-selected="true">Signup</button>
              </li>
            </ul>
            
            <div class="tab-content" id="pills-tabContent2">
              <!-- Login Tab -->
              <div class="tab-pane fade show active" id="pills-homes" role="tabpanel"
                aria-labelledby="pills-home-tab2">
                <div class="wsus__login">
                  <form @submit.prevent="handleLogin">
                    <div class="wsus__login_input" :class="{ 'has-error': authStore.getError('email') }">
                      <i class="far fa-envelope"></i>
                      <input v-model="loginForm.email" type="email" placeholder="Email" required>
                    </div>
                    <ErrorMessage :message="authStore.getError('email')" />
                    <div class="wsus__login_input" :class="{ 'has-error': authStore.getError('password') }">
                      <i class="fas fa-key"></i>
                      <input v-model="loginForm.password" type="password" placeholder="Password" required>
                    </div>
                    <ErrorMessage :message="authStore.getError('password')" />
                    <div class="wsus__login_save">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                      </div>
                      <router-link to="/forgot-password" class="forget_p">Forgot password?</router-link>
                    </div>
                    <button class="common_btn" type="submit" :disabled="authStore.isLoading">
                      <span v-if="!authStore.isLoading">Login</span>
                      <span v-else>Logging in...</span>
                    </button>
                  </form>
                </div>
              </div>
              
              <!-- Register Tab -->
              <div class="tab-pane fade" id="pills-profiles" role="tabpanel"
                aria-labelledby="pills-profile-tab2">
                <div class="wsus__login">
                  <form @submit.prevent="handleRegister">
                    <div class="wsus__login_input" :class="{ 'has-error': authStore.getError('name') }">
                      <i class="fas fa-user-tie"></i>
                      <input v-model="registerForm.name" type="text" placeholder="Name" required>
                    </div>
                    <ErrorMessage :message="authStore.getError('name')" />
                    <div class="wsus__login_input" :class="{ 'has-error': authStore.getError('email') }">
                      <i class="far fa-envelope"></i>
                      <input v-model="registerForm.email" type="email" placeholder="Email" required>
                    </div>
                    <ErrorMessage :message="authStore.getError('email')" />
                    <div class="wsus__login_input" :class="{ 'has-error': authStore.getError('password') }">
                      <i class="fas fa-key"></i>
                      <input v-model="registerForm.password" type="password" placeholder="Password" required>
                    </div>
                    <ErrorMessage :message="authStore.getError('password')" />
                    <div class="wsus__login_input" :class="{ 'has-error': authStore.getError('password_confirmation') }">
                      <i class="fas fa-key"></i>
                      <input v-model="registerForm.password_confirmation" type="password" 
                        placeholder="Confirm Password" required>
                    </div>
                    <ErrorMessage :message="authStore.getError('password_confirmation')" />
                    
                    <div class="wsus__login_save">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                        <label class="form-check-label" for="agreeTerms">
                          I agree to the <router-link to="/terms">Terms of Service</router-link>
                        </label>
                      </div>
                    </div>
                    <button class="common_btn" type="submit" :disabled="authStore.isLoading">
                      <span v-if="!authStore.isLoading">Sign Up</span>
                      <span v-else>Registering...</span>
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/useAuthStore';
import ErrorMessage from '@/components/layouts/ErrorMessage.vue';

const router = useRouter();
const authStore = useAuthStore();

onMounted(() => {
  authStore.resetValidationErrors();
});

const loginForm = reactive({
  email: '',
  password: ''
});

const registerForm = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
});

const handleLogin = async () => {
  const { success } = await authStore.login(loginForm);
  if (success) {
    router.push('/');
  }
};

const handleRegister = async () => {
  const { success } = await authStore.register(registerForm);
  if (success) {
    router.push('/');
  }
};
</script>

<style scoped>
.wsus__login_input.has-error {
  border-color: #dc3545;
}

.wsus__login_input.has-error input {
  color: #dc3545;
}

.invalid-feedback {
  color: #dc3545;
  font-size: 0.875em;
  margin-top: 0.25rem;
}
</style>