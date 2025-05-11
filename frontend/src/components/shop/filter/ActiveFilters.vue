<template>
    <div v-if="hasActiveFilters" class="active-filters mb-4">
      <div class="d-flex flex-wrap align-items-center gap-2">
        <span class="fw-bold">Bộ lọc:</span>
        
        <template v-if="route.query.category_id">
          <span class="badge bg-primary d-flex align-items-center">
            Danh mục: {{ getCategoryName(route.query.category_id) }}
            <button @click="removeFilter('category_id')" class="ms-2 bg-transparent border-0 text-white">
              <i class="fas fa-times"></i>
            </button>
          </span>
        </template>
        
        <template v-if="route.query.brand_id">
          <span class="badge bg-primary d-flex align-items-center">
            Thương hiệu: {{ getBrandName(route.query.brand_id) }}
            <button @click="removeFilter('brand_id')" class="ms-2 bg-transparent border-0 text-white">
              <i class="fas fa-times"></i>
            </button>
          </span>
        </template>
        
        <template v-if="route.query.size_id">
          <span class="badge bg-primary d-flex align-items-center">
            Kích thước: {{ getSizeNames(route.query.size_id) }}
            <button @click="removeFilter('size_id')" class="ms-2 bg-transparent border-0 text-white">
              <i class="fas fa-times"></i>
            </button>
          </span>
        </template>
        
        <template v-if="route.query.color_id">
          <span class="badge bg-primary d-flex align-items-center">
            Màu sắc: {{ getColorNames(route.query.color_id) }}
            <button @click="removeFilter('color_id')" class="ms-2 bg-transparent border-0 text-white">
              <i class="fas fa-times"></i>
            </button>
          </span>
        </template>
        
        <template v-if="route.query.min_price || route.query.max_price">
          <span class="badge bg-primary d-flex align-items-center">
            Giá: {{ priceRangeText }}
            <button @click="removePriceFilter" class="ms-2 bg-transparent border-0 text-white">
              <i class="fas fa-times"></i>
            </button>
          </span>
        </template>
        
        <button @click="resetAllFilters" class="btn btn-sm btn-outline-danger ms-2">
          <i class="fas fa-trash-alt me-1"></i> Xóa tất cả
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import { useProductsStore } from '@/stores/useProductsStore';
  
  const route = useRoute();
  const router = useRouter();
  const productsStore = useProductsStore();
  
  const hasActiveFilters = computed(() => {
    return Object.keys(route.query).some(key => 
      ['category_id', 'brand_id', 'size_id', 'color_id', 'min_price', 'max_price'].includes(key)
    );
  });
  
  const getCategoryName = (id) => {
    const category = productsStore.categories.find(c => c.id == id);
    return category?.name || id;
  };
  
  const getBrandName = (id) => {
    const brand = productsStore.brands.find(b => b.id == id);
    return brand?.name || id;
  };
  
  const getSizeNames = (ids) => {
    const idArray = ids.split(',');
    return idArray.map(id => {
      const size = productsStore.sizes.find(s => s.id == id);
      return size?.name || id;
    }).join(', ');
  };
  
  const getColorNames = (ids) => {
    const idArray = ids.split(',');
    return idArray.map(id => {
      const color = productsStore.colors.find(c => c.id == id);
      return color?.name || id;
    }).join(', ');
  };
  
  const priceRangeText = computed(() => {
    const min = route.query.min_price ? formatPrice(route.query.min_price) : '0';
    const max = route.query.max_price ? formatPrice(route.query.max_price) : '∞';
    return `${min} - ${max}`;
  });
  
  const formatPrice = (price) => {
    return new Intl.NumberFormat('vi-VN').format(price);
  };
  
  const removeFilter = (key) => {
    const query = { ...route.query };
    delete query[key];
    delete query.page;
    router.push({ query });
  };
  
  const removePriceFilter = () => {
    const query = { ...route.query };
    delete query.min_price;
    delete query.max_price;
    delete query.page;
    router.push({ query });
  };
  
  const resetAllFilters = () => {
    router.push({ 
      path: route.path,
      query: { sort: route.query.sort } // Giữ lại sắp xếp nếu có
    });
  };
  </script>
  
  <style scoped>
  .active-filters {
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 5px;
  }
  </style>