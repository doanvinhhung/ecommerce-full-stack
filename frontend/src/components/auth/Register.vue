<template>
    
</template>
<!-- <template>
    <section id="wsus__register">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-5 col-lg-6 col-md-8">
            <div class="wsus__register_area">
              <h2>Create Your Account</h2>
              
              <form @submit.prevent="handleRegister">
                
                <div class="wsus__input_box">
                  <i class="fas fa-user"></i>
                  <input 
                    v-model="form.name" 
                    type="text" 
                    placeholder="Full Name"
                    required
                  >
                </div>
             
                <div class="wsus__input_box">
                  <i class="far fa-envelope"></i>
                  <input 
                    v-model="form.email" 
                    type="email" 
                    placeholder="Email Address"
                    required
                  >
                </div>
                
             
                <div class="wsus__input_box">
                  <i class="fas fa-key"></i>
                  <input 
                    v-model="form.password" 
                    type="password" 
                    placeholder="Password"
                    required
                    minlength="8"
                  >
                </div>
                
      
                <div class="wsus__input_box">
                  <i class="fas fa-key"></i>
                  <input 
                    v-model="form.password_confirmation" 
                    type="password" 
                    placeholder="Confirm Password"
                    required
                    minlength="8"
                  >
                </div>
            
                <RenderValidationErrors :errors="authStore.validationErrors" />
                
              
                <div class="wsus__terms">
                  <input 
                    id="terms" 
                    type="checkbox" 
                    v-model="form.agree_terms"
                    required
                  >
                  <label for="terms">
                    I agree to the <router-link to="/terms">Terms of Service</router-link>
                  </label>
                </div>
                
            
                <button 
                  type="submit" 
                  class="common_btn"
                  :disabled="authStore.isLoading"
                >
                  <span v-if="!authStore.isLoading">Register</span>
                  <Spinner v-else />
                </button>
                
           
                <div class="wsus__login_link">
                  Already have an account? <router-link to="/login">Login</router-link>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template> -->
  
  <!-- <script setup>
  import { reactive } from 'vue'
  import { useRouter } from 'vue-router'
  import { useAuthStore } from '@/stores/useAuthStore'
  import Spinner from '@/components/layouts/Spinner.vue'
  import RenderValidationErrors from '@/components/layouts/RenderValidationErrors.vue'
  
  const authStore = useAuthStore()
  const router = useRouter()
  
  const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    agree_terms: false
  })
  
  const handleRegister = async () => {
    await authStore.register(form)
    
    // Nếu đăng ký thành công, chuyển hướng
    if (authStore.isLoggedIn) {
      router.push('/home') // Hoặc trang chính
    }
  }
  </script>
  
  <style scoped>
  .wsus__register_area {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
  }
  
  .wsus__input_box {
    position: relative;
    margin-bottom: 20px;
  }
  
  .wsus__input_box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #777;
  }
  
  .wsus__input_box input {
    width: 100%;
    padding: 12px 15px 12px 40px;
    border: 1px solid #ddd;
    border-radius: 4px;
  }
  
  .wsus__terms {
    margin: 20px 0;
    display: flex;
    align-items: center;
  }
  
  .wsus__terms input {
    margin-right: 10px;
  }
  
  .wsus__login_link {
    margin-top: 20px;
    text-align: center;
  }
  </style> -->