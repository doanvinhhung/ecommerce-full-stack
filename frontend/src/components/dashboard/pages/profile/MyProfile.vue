<script setup>
import { ref, onMounted, watch } from 'vue';
import { useAuthStore } from '@/stores/useAuthStore';
import { useToast } from 'vue-toastification';
import SideBar from '../../layouts/SideBar.vue';

const authStore = useAuthStore();
const toast = useToast();

// Initialize form with current user data
const profileForm = ref({
  name: '',
  email: '',
  phone: '',
  address: '',
  city: '',
  country: '',
  zip: ''
});

const passwordForm = ref({
  current_password: '',
  password: '',
  password_confirmation: ''
});

const selectedFile = ref(null);
const previewImage = ref('');

// Initialize form data when component mounts or user changes
const initializeFormData = () => {
  if (authStore.user) {
    profileForm.value = {
      name: authStore.user.name || '',
      email: authStore.user.email || '',
      phone: authStore.user.phone || '',
      address: authStore.user.address || '',
      city: authStore.user.city || '',
      country: authStore.user.country || '',
      zip: authStore.user.zip || ''
    };
    previewImage.value = authStore.user.profile_image || '/assets/images/logo.png';
  }
};

onMounted(() => {
  authStore.resetValidationErrors();
  initializeFormData();
});

// Watch for changes in authStore.user
watch(() => authStore.user, () => {
  initializeFormData();
}, { deep: true });

const handleFileChange = (event) => {
  const input = event.target;
  if (input.files && input.files[0]) {
    selectedFile.value = input.files[0];
    previewImage.value = URL.createObjectURL(input.files[0]);
  }
};

const updateProfile = async () => {
  try {
    const formData = new FormData();
    
    // Append all profile data
    Object.entries(profileForm.value).forEach(([key, value]) => {
      if (value !== null && value !== undefined) {
        formData.append(key, value);
      }
    });
    
    // Append profile image if selected
    if (selectedFile.value) {
      formData.append('profile_image', selectedFile.value);
    }
    
    await authStore.updateProfile(formData);
  } catch (error) {
    console.error('Profile update error:', error);
  }
};

const changePassword = async () => {
  try {
    await authStore.changePassword(passwordForm.value);
    // Reset password form on success
    passwordForm.value = {
      current_password: '',
      password: '',
      password_confirmation: ''
    };
  } catch (error) {
    console.error('Password change error:', error);
  }
};
</script>

<template>
  <SideBar />
  
  <div class="row">
    <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
      <div class="dashboard_content mt-2 mt-md-0">
        <h3><i class="far fa-user"></i> profile</h3>
        <div class="wsus__dashboard_profile">
          <div class="wsus__dash_pro_area">
            <h4>basic information</h4>
            <form @submit.prevent="updateProfile">
              <div class="row">
                <div class="col-xl-9">
                  <div class="row">
                    <div class="col-xl-6 col-md-6">
                      <div class="wsus__dash_pro_single" :class="{ 'has-error': authStore.getError('name') }">
                        <i class="fas fa-user-tie"></i>
                        <input v-model="profileForm.name" type="text" placeholder="Full Name">
                        <span class="invalid-feedback" v-if="authStore.getError('name')">
                          {{ authStore.getError('name') }}
                        </span>
                      </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                      <div class="wsus__dash_pro_single" :class="{ 'has-error': authStore.getError('phone') }">
                        <i class="far fa-phone-alt"></i>
                        <input v-model="profileForm.phone" type="text" placeholder="Phone">
                        <span class="invalid-feedback" v-if="authStore.getError('phone')">
                          {{ authStore.getError('phone') }}
                        </span>
                      </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                      <div class="wsus__dash_pro_single">
                        <i class="fal fa-envelope-open"></i>
                        <input v-model="profileForm.email" type="email" placeholder="Email" disabled>
                      </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                      <div class="wsus__dash_pro_single" :class="{ 'has-error': authStore.getError('address') }">
                        <i class="fas fa-map-marker-alt"></i>
                        <input v-model="profileForm.address" type="text" placeholder="Address">
                        <span class="invalid-feedback" v-if="authStore.getError('address')">
                          {{ authStore.getError('address') }}
                        </span>
                      </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                      <div class="wsus__dash_pro_single" :class="{ 'has-error': authStore.getError('city') }">
                        <i class="fas fa-city"></i>
                        <input v-model="profileForm.city" type="text" placeholder="City">
                        <span class="invalid-feedback" v-if="authStore.getError('city')">
                          {{ authStore.getError('city') }}
                        </span>
                      </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                      <div class="wsus__dash_pro_single" :class="{ 'has-error': authStore.getError('country') }">
                        <i class="fas fa-flag"></i>
                        <input v-model="profileForm.country" type="text" placeholder="Country">
                        <span class="invalid-feedback" v-if="authStore.getError('country')">
                          {{ authStore.getError('country') }}
                        </span>
                      </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                      <div class="wsus__dash_pro_single" :class="{ 'has-error': authStore.getError('zip') }">
                        <i class="fas fa-mail-bulk"></i>
                        <input v-model="profileForm.zip" type="text" placeholder="Zip Code">
                        <span class="invalid-feedback" v-if="authStore.getError('zip')">
                          {{ authStore.getError('zip') }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-md-6">
                  <div class="wsus__dash_pro_img">
                    <img :src="previewImage" alt="Profile Image" class="img-fluid w-100">
                    <input type="file" @change="handleFileChange" accept="image/*">
                    <span class="invalid-feedback" v-if="authStore.getError('profile_image')">
                      {{ authStore.getError('profile_image') }}
                    </span>
                  </div>
                </div>
                <div class="col-xl-12">
                  <button class="common_btn mb-4 mt-2" type="submit" :disabled="authStore.isLoading">
                    <span v-if="!authStore.isLoading">Update Profile</span>
                    <span v-else>Saving...</span>
                  </button>
                </div>
              </div>
            </form>

            <div class="wsus__dash_pass_change mt-5">
              <h4>Change Password</h4>
              <form @submit.prevent="changePassword">
                <div class="row">
                  <div class="col-xl-4 col-md-6">
                    <div class="wsus__dash_pro_single" :class="{ 'has-error': authStore.getError('current_password') }">
                      <i class="fas fa-unlock-alt"></i>
                      <input v-model="passwordForm.current_password" type="password" placeholder="Current Password" required>
                      <span class="invalid-feedback" v-if="authStore.getError('current_password')">
                        {{ authStore.getError('current_password') }}
                      </span>
                    </div>
                  </div>
                  <div class="col-xl-4 col-md-6">
                    <div class="wsus__dash_pro_single" :class="{ 'has-error': authStore.getError('password') }">
                      <i class="fas fa-lock-alt"></i>
                      <input v-model="passwordForm.password" type="password" placeholder="New Password" required>
                      <span class="invalid-feedback" v-if="authStore.getError('password')">
                        {{ authStore.getError('password') }}
                      </span>
                    </div>
                  </div>
                  <div class="col-xl-4">
                    <div class="wsus__dash_pro_single" :class="{ 'has-error': authStore.getError('password_confirmation') }">
                      <i class="fas fa-lock-alt"></i>
                      <input v-model="passwordForm.password_confirmation" type="password" placeholder="Confirm Password" required>
                      <span class="invalid-feedback" v-if="authStore.getError('password_confirmation')">
                        {{ authStore.getError('password_confirmation') }}
                      </span>
                    </div>
                  </div>
                  <div class="col-xl-12">
                    <button class="common_btn" type="submit" :disabled="authStore.isLoading">
                      <span v-if="!authStore.isLoading">Change Password</span>
                      <span v-else>Updating...</span>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.wsus__dash_pro_single.has-error {
  border-color: #dc3545;
}

.wsus__dash_pro_single.has-error input {
  color: #dc3545;
}

.invalid-feedback {
  color: #dc3545;
  font-size: 0.875em;
  margin-top: 0.25rem;
  display: block;
}
</style>