<template>
    <div class="col-xl-5 col-md-6 col-lg-4 d-none d-lg-block">
      <div class="wsus__search">
        <form @submit.prevent="searchProducts">
          <input type="text" placeholder="Search..." v-model="searchTerm">
          <button type="submit" :disabled="!searchTerm.trim()">
            <i class="far fa-search"></i>
          </button>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  
  const router = useRouter();
  const searchTerm = ref('');
  
  const searchProducts = () => {
    const query = { ...router.currentRoute.value.query };
    
    if (searchTerm.value.trim()) {
      query.search = searchTerm.value.trim();
      // Xóa các filter khác khi tìm kiếm
      delete query.categories;
      delete query.brands;
      delete query.colors;
      delete query.sizes;
      delete query.min_price;
      delete query.max_price;
    } else {
      delete query.search;
    }
  
    router.push({ 
      path: '/shop',
      query: query
    });
  };
  </script>