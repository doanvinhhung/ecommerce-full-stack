<template>

<div class="dashboard_sidebar" :class="{ show_dash_menu: showDashMenu }">

    <span class="close_icon" @click="toggleSidebar">
      <i class="far fa-bars dash_bar"></i>
      <i class="far fa-times dash_close"></i>
    </span>

    <router-link to="/dashboard" class="dash_logo">
      <img src="/assets/images/logo.png" alt="logo" class="img-fluid">
    </router-link>
    <ul class="dashboard_link">
      <li>
        <router-link to="/dashboard" active-class="active">
          <i class="fas fa-tachometer"></i> Dashboard
        </router-link>
      </li>
      <li>
        <router-link to="/user/orders" active-class="active">
          <i class="fas fa-list-ul"></i> Orders
        </router-link>
      </li>
      <li>
        <router-link to="/my-profile" active-class="active">
          <i class="far fa-user"></i> My Profile
        </router-link>
      </li>
         <li>
        <router-link to="/" active-class="active">
          <i class="far fa-user"></i>Back Home 
        </router-link>
      </li>
      <li>
        <a href="#" @click.prevent="logout">
          <i class="far fa-sign-out-alt"></i> Log out
        </a>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/useAuthStore';
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
// logic
import { ref } from 'vue';

const showDashMenu = ref(false);

const toggleSidebar = () => {
  showDashMenu.value = !showDashMenu.value;
};

const authStore = useAuthStore();
const router = useRouter();

const user = authStore.user; // Lấy thông tin user từ store

const logout = async () => {
  await authStore.logout();
  router.push('/login');
};



// onMounted(() => {
//   if (typeof $ !== 'undefined') {
//     // MINI CART
//     //*==========DASHBOARD SIDEBAR==========  
//     $('.close_icon').on('click', function () {
//       $('.dashboard_sidebar').toggleClass('show_dash_menu');
//     });

//     $('.close_icon').on('click', function () {
//       $('.dash_close').toggleClass('dash_opasity');
//     });

//   } else {
//     console.warn('jQuery not loaded.');
//   }
// });
</script>