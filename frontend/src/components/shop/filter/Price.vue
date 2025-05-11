<template>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse"
        data-bs-target="#collapsePrice" aria-expanded="true">
        Giá
        <span v-if="isPriceFilterActive" class="badge bg-danger ms-2">Đang lọc</span>
      </button>
    </h2>
    <div id="collapsePrice" class="accordion-collapse collapse show">
      <div class="accordion-body">
        <div class="price-slider-container">
          <div class="price-display d-flex justify-content-between mb-2">
            <span>Từ: {{ formatPrice(tempPriceRange[0]) }}</span>
            <span>Đến: {{ formatPrice(tempPriceRange[1]) }}</span>
          </div>
          
          <div class="price-slider">
            <input type="range" class="form-range" min="0" :max="maxPrice" step="100000"
              v-model.number="tempPriceRange[0]" @input="updateTempRange(0)">
            <input type="range" class="form-range" min="0" :max="maxPrice" step="100000"
              v-model.number="tempPriceRange[1]" @input="updateTempRange(1)">
          </div>
          
          <div class="price-inputs d-flex gap-2 mt-3">
            <div class="form-group flex-grow-1">
              <label>Từ</label>
              <input type="number" class="form-control" v-model.number="tempPriceRange[0]" 
                @change="validateInput(0)" min="0" :max="tempPriceRange[1]">
            </div>
            <div class="form-group flex-grow-1">
              <label>Đến</label>
              <input type="number" class="form-control" v-model.number="tempPriceRange[1]" 
                @change="validateInput(1)" :min="tempPriceRange[0]" :max="maxPrice">
            </div>
          </div>
          
          <div class="d-flex gap-2 mt-3">
            <button class="btn btn-primary flex-grow-1" @click="applyPriceFilter">
              <i class="fas fa-filter me-1"></i> Lọc
            </button>
            <button v-if="isPriceFilterActive" class="btn btn-outline-danger" @click="resetPriceFilter">
              <i class="fas fa-times me-1"></i> Xóa
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

// Cấu hình
const maxPrice = ref(10000000); // 10 triệu
const tempPriceRange = ref([0, maxPrice.value]); // Giá trị tạm thời khi chỉnh slider

// Khởi tạo giá trị từ URL
if (route.query.min_price || route.query.max_price) {
  tempPriceRange.value = [
    parseInt(route.query.min_price) || 0,
    Math.min(parseInt(route.query.max_price) || maxPrice.value, maxPrice.value)
  ];
}

// Kiểm tra xem có filter giá đang active không
const isPriceFilterActive = computed(() => {
  return route.query.min_price || route.query.max_price;
});

// Cập nhật giá trị tạm thời và đảm bảo min <= max
const updateTempRange = (index) => {
  if (index === 0 && tempPriceRange.value[0] > tempPriceRange.value[1]) {
    tempPriceRange.value[1] = tempPriceRange.value[0];
  } else if (index === 1 && tempPriceRange.value[1] < tempPriceRange.value[0]) {
    tempPriceRange.value[0] = tempPriceRange.value[1];
  }
};

// Validate input khi nhập trực tiếp
const validateInput = (index) => {
  let value = tempPriceRange.value[index];
  
  if (isNaN(value)) {
    tempPriceRange.value[index] = index === 0 ? 0 : maxPrice.value;
    return;
  }
  
  if (index === 0) {
    if (value < 0) value = 0;
    if (value > tempPriceRange.value[1]) value = tempPriceRange.value[1];
  } else {
    if (value < tempPriceRange.value[0]) value = tempPriceRange.value[0];
    if (value > maxPrice.value) value = maxPrice.value;
  }
  
  tempPriceRange.value[index] = value;
};

// Áp dụng bộ lọc giá
const applyPriceFilter = () => {
  const query = { ...route.query };
  
  if (tempPriceRange.value[0] > 0) {
    query.min_price = tempPriceRange.value[0];
  } else {
    delete query.min_price;
  }
  
  if (tempPriceRange.value[1] < maxPrice.value) {
    query.max_price = tempPriceRange.value[1];
  } else {
    delete query.max_price;
  }
  
  // Reset về trang 1 khi áp dụng bộ lọc mới
  delete query.page;
  
  router.push({ query });
};

// Reset bộ lọc giá
const resetPriceFilter = () => {
  tempPriceRange.value = [0, maxPrice.value];
  const query = { ...route.query };
  delete query.min_price;
  delete query.max_price;
  delete query.page;
  router.push({ query });
};

// Hàm format giá
const formatPrice = (price) => {
  return new Intl.NumberFormat('vi-VN').format(price) + ' ₫';
};
</script>

<style scoped>
.price-slider-container {
  padding: 0 10px;
}

.price-slider {
  position: relative;
  height: 40px;
}

.price-slider input[type="range"] {
  position: absolute;
  width: 100%;
  pointer-events: none;
  -webkit-appearance: none;
  height: 4px;
  background: transparent;
}

.price-slider input[type="range"]::-webkit-slider-thumb {
  pointer-events: all;
  -webkit-appearance: none;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: #0d6efd;
  cursor: pointer;
  margin-top: -7px;
}

.price-display span {
  font-size: 0.9rem;
  font-weight: 500;
}

.price-inputs input {
  text-align: right;
}
</style>