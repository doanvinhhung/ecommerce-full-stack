<template>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse"
        data-bs-target="#collapseColor" aria-expanded="true">
        Màu sắc ({{ totalColorProducts }})
      </button>
    </h2>
    <div id="collapseColor" class="accordion-collapse collapse show">
      <div class="accordion-body p-4">
        <div class="two-column-grid">
          <div v-for="color in colorsWithCount" :key="color.id" class="color-item">
            <div class="form-check">
              <input 
                class="form-check-input" 
                type="checkbox" 
                :id="'color-'+color.id"
                :checked="isSelected(color.id)"
                @change="toggleColor(color.id)"
              >
              <label class="form-check-label" :for="'color-'+color.id">
                <span 
                  class="color-preview"
                  :style="{ backgroundColor: color.name || '#ccc' }"
                ></span>
                <span class="color-text">{{ color.name }} ({{ color.count }})</span>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Phần script giữ nguyên như trước
import { computed } from 'vue';
import { useProductsStore } from '@/stores/useProductsStore';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const productsStore = useProductsStore();

const colorsWithCount = computed(() => {
  return productsStore.colors.map(color => {
    const count = productsStore.products.filter(product => 
      product.colors && product.colors.some(c => c.id === color.id)
    ).length;
    return { ...color, count };
  }).filter(color => color.count > 0);
});

const totalColorProducts = computed(() => {
  return colorsWithCount.value.reduce((total, color) => total + color.count, 0);
});

const isSelected = (colorId) => {
  if (!route.query.color_id) return false;
  return route.query.color_id.split(',').includes(colorId.toString());
};

const toggleColor = (colorId) => {
  const query = { ...route.query };
  let currentColors = [];
  
  if (query.color_id) {
    currentColors = query.color_id.split(',');
  }
  
  const index = currentColors.indexOf(colorId.toString());
  if (index > -1) {
    currentColors.splice(index, 1);
  } else {
    currentColors.push(colorId.toString());
  }
  
  if (currentColors.length > 0) {
    query.color_id = currentColors.join(',');
  } else {
    delete query.color_id;
  }
  
  router.push({ query });
};
</script>

<style scoped>
.two-column-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 8px;
}

.color-item {
  margin: 0;
  padding: 4px 0;
}

.form-check {
  display: flex;
  align-items: center;
  margin: 0;
  min-height: 24px;
}

.form-check-input {
  margin-top: 0;
  margin-right: 8px;
  width: 16px;
  height: 16px;
}

.form-check-label {
  display: flex;
  align-items: center;
  cursor: pointer;
  width: 100%;
}

.color-preview {
  width: 18px;
  height: 18px;
  border-radius: 50%;
  border: 1px solid #ddd;
  margin-right: 8px;
  flex-shrink: 0;
}

.color-text {
  font-size: 0.8125rem; /* Nhỏ hơn (13px) */
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>