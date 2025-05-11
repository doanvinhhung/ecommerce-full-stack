<template>
  <!--=============================
    DASHBOARD MENU START
  ==============================-->
  <div class="wsus__dashboard_menu">
    <div class="wsusd__dashboard_user">
      <!-- Sửa chỗ này: thêm default image nếu không có avatar -->
      <img :src="user?.profile_image || '/assets/images/default-avatar.jpg'" alt="avatar" class="img-fluid">
      <!-- Hiển thị tên user hoặc 'Guest' nếu chưa login -->
      <p>{{ user?.name || 'Khách' }}</p>
    </div>
  </div>
  <!--=============================
    DASHBOARD MENU END
  ==============================-->
  
  <section id="wsus__dashboard">
    <div class="container-fluid">
      <SideBar />
      <!-- Show Index component only for dashboard root -->
      <Index v-if="$route.path === '/dashboard'" />
      <router-view v-else />
    </div>
  </section>
  
  <div class="wsus__scroll_btn">
    <i class="fas fa-chevron-up"></i>
  </div>
</template>

<script setup>
import Index from '../pages/Index.vue';
import SideBar from './SideBar.vue';
import { useAuthStore } from '@/stores/useAuthStore';
import { computed, onMounted } from 'vue';

const authStore = useAuthStore();

// Sử dụng computed để tự động cập nhật khi dữ liệu thay đổi
const user = computed(() => authStore.user);

// onMounted(() => {
//   setTimeout(() => {
//     const $closeIcon = $('.close_icon');

//     // Gỡ sự kiện cũ để tránh bị gắn trùng nhiều lần
//     $closeIcon.off('click');

//     $closeIcon.on('click', function () {
//       $('.dashboard_sidebar').toggleClass('show_dash_menu');
//       $('.dash_close').toggleClass('dash_opasity');
//     });
//   }, 0);
// });

</script>