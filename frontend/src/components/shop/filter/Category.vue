<template>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse"
        data-bs-target="#collapseCategory" aria-expanded="true">
        Danh mục ({{ totalCategoryProducts }})
      </button>
    </h2>
    <div id="collapseCategory" class="accordion-collapse collapse show">
      <div class="accordion-body">
        <div class="form-check" v-for="category in categoriesWithCount" :key="category.id">
          <input class="form-check-input" type="checkbox" 
            :id="'category-'+category.id"
            :checked="isSelected(category.id)"
            @change="toggleCategory(category.id)">
          <label class="form-check-label" :for="'category-'+category.id">
            {{ category.name }} ({{ category.count }})
          </label>
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

const categoriesWithCount = computed(() => {
  return productsStore.categories.map(category => {
    const count = productsStore.products.filter(p => 
      p.category && p.category.id === category.id
    ).length;
    return { ...category, count };
  }).filter(category => category.count > 0);
});

const totalCategoryProducts = computed(() => {
  return categoriesWithCount.value.reduce((total, category) => total + category.count, 0);
});

const isSelected = (categoryId) => {
  if (!route.query.category_id) return false;
  return route.query.category_id.split(',').includes(categoryId.toString());
};

const toggleCategory = (categoryId) => {
  const query = { ...route.query };
  let currentCategories = [];
  
  if (query.category_id) {
    currentCategories = query.category_id.split(',');
  }
  
  const index = currentCategories.indexOf(categoryId.toString());
  if (index > -1) {
    currentCategories.splice(index, 1);
  } else {
    currentCategories.push(categoryId.toString());
  }
  
  if (currentCategories.length > 0) {
    query.category_id = currentCategories.join(',');
  } else {
    delete query.category_id;
  }
  
  router.push({ query });
};
</script>