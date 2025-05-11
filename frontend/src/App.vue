<template>
    <!-- Layout mặc định (có header/footer) -->
    <div v-if="$route.meta.layout !== 'dashboard'">
      <Header />
      <Navbar />
      <router-view />
      <Footer />
          <!--============================
        SCROLL BUTTON START
    ==============================-->
    <div class="wsus__scroll_btn">
        <i class="fas fa-chevron-up"></i>
    </div>
    <!--============================
        SCROLL BUTTON  END
    ==============================-->
    </div>
    
    <!-- Layout dashboard (không header/footer) -->
    <div v-else>
      <router-view />
          <!--============================
        SCROLL BUTTON START
    ==============================-->
    <div class="wsus__scroll_btn">
        <i class="fas fa-chevron-up"></i>
    </div>
    <!--============================
        SCROLL BUTTON  END
    ==============================-->
    </div>
  </template>
  
  <script setup>
  import Header from '@/components/layouts/Header.vue'
  import Navbar from '@/components/layouts/Navbar.vue'
  import Footer from '@/components/layouts/Footer.vue'
  </script>