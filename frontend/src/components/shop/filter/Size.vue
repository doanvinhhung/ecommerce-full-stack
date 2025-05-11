<template>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse"
        data-bs-target="#collapseSize" aria-expanded="true">
        Kích thước ({{ totalSizeProducts }})
        <span v-if="activeSizeCount > 0" class="badge bg-danger ms-2">
          {{ activeSizeCount }}
        </span>
      </button>
    </h2>
    <div id="collapseSize" class="accordion-collapse collapse show">
      <div class="accordion-body">
        <div class="size-filter-container">
          <div v-for="size in sizesWithCount" :key="size.id" 
            class="size-option"
            :class="{ 'size-selected': isSelected(size.id) }"
            @click="toggleSize(size.id)"
          >
            {{ size.name }}
            <span class="size-count">({{ size.count }})</span>
          </div>
        </div>
        <div v-if="activeSizeCount > 0" class="mt-2">
          <button class="btn btn-sm btn-outline-danger" @click="clearSizeFilter">
            <i class="fas fa-times me-1"></i> Xóa lọc
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useProductsStore } from '@/stores/useProductsStore';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const productsStore = useProductsStore();

const sizesWithCount = computed(() => {
  return productsStore.filterOptions.sizes;
});

const totalSizeProducts = computed(() => {
  return sizesWithCount.value.reduce((total, size) => total + size.count, 0);
});

const activeSizeCount = computed(() => {
  return route.query.size_id ? route.query.size_id.split(',').length : 0;
});

const isSelected = (sizeId) => {
  if (!route.query.size_id) return false;
  return route.query.size_id.split(',').includes(sizeId.toString());
};

const toggleSize = (sizeId) => {
  const query = { ...route.query };
  let currentSizes = [];
  
  if (query.size_id) {
    currentSizes = query.size_id.split(',');
  }
  
  const index = currentSizes.indexOf(sizeId.toString());
  if (index > -1) {
    currentSizes.splice(index, 1);
  } else {
    currentSizes.push(sizeId.toString());
  }
  
  if (currentSizes.length > 0) {
    query.size_id = currentSizes.join(',');
  } else {
    delete query.size_id;
  }
  
  // Reset về trang 1 khi thay đổi bộ lọc
  delete query.page;
  
  router.push({ query });
};

const clearSizeFilter = () => {
  const query = { ...route.query };
  delete query.size_id;
  delete query.page;
  router.push({ query });
};
</script>

<style scoped>
.size-filter-container {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.size-option {
  padding: 5px 12px;
  border: 1px solid #dee2e6;
  border-radius: 4px;
  background-color: white;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 14px;
}

.size-option:hover {
  background-color: #f8f9fa;
  border-color: #adb5bd;
}

.size-selected {
  background-color: #0d6efd;
  color: white;
  border-color: #0d6efd;
}

.size-count {
  font-size: 12px;
  margin-left: 4px;
}
</style>