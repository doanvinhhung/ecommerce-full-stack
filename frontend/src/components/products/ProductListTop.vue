<template>
  <!-- products list header -->
  <div class="row">
    <div class="col-xl-12">
      <div class="wsus__section_header for_md">
        <h3>Top Categories Of The Month</h3>
        <div class="monthly_top_filter">
          <button 
            v-for="category in productsStore.categories" 
            :key="category.id" 
            :class="{ active: selectedCategory === category.id }"
            @click="filterByCategory(category.id)"
          >
            {{ category.name }}
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-12 col-lg-12">
    <div v-if="isLoading" class="loading-spinner">Loading...</div>

    <div v-else class="row grid">
      <ProductListItem 
        v-for="product in topProducts" 
        :key="product.id" 
        :product="product" 
      />
      <div v-if="isFetching" class="text-center my-3">
        <span>Đang tải thêm sản phẩm...</span>
      </div>
      <div v-if="!hasMore && topProducts.length > 0" class="text-center my-3 text-muted">
        <span>Đã hiển thị tất cả sản phẩm</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import ProductListItem from "./ProductListItem.vue";
import { useProductsStore } from "@/stores/useProductsStore";
import { onMounted, onUnmounted, ref, watch } from "vue";
import axios from "axios";
import { BASE_URL } from "@/helpers/config";

const productsStore = useProductsStore();
const isLoading = ref(true);
const isFetching = ref(false);
const hasMore = ref(true);
const topProducts = ref([]);
const selectedCategory = ref(null);

const page = ref(1);
const perPage = 6;

// Hàm tải sản phẩm theo trang và danh mục
const fetchTopProducts = async () => {
  if (isFetching.value || !hasMore.value) return;

  isFetching.value = true;
  try {
    const params = {
      page: page.value,
      limit: perPage,
    };
    if (selectedCategory.value) {
      params.category_id = selectedCategory.value;
    }

    const response = await axios.get(`${BASE_URL}/products`, { params });
    const products = response.data.data;

    if (page.value === 1) topProducts.value = [];
    topProducts.value.push(...products);

    const currentPage = response.data.current_page || page.value;
    const lastPage = response.data.last_page || 1;
    hasMore.value = currentPage < lastPage;

    page.value++;
  } catch (error) {
    console.error("Lỗi khi tải sản phẩm top:", error);
  } finally {
    isFetching.value = false;
    isLoading.value = false;
  }
};

// Lọc theo danh mục
const filterByCategory = (categoryId) => {
  selectedCategory.value = categoryId;
  page.value = 1;
  hasMore.value = true;
  fetchTopProducts();
};

// Cuộn vô hạn
const handleScroll = () => {
  const bottomReached =
    window.innerHeight + window.scrollY >= document.body.offsetHeight - 100;
  if (bottomReached) fetchTopProducts();
};

onMounted(async () => {
  await productsStore.fetchAllProducts(); // Lấy categories
  await fetchTopProducts();
  window.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
  window.removeEventListener("scroll", handleScroll);
});
</script>

<style scoped>
.loading-spinner {
  text-align: center;
  font-size: 18px;
  font-weight: bold;
  padding: 20px;
}
.active {
  background-color: #ff5733;
  color: white;
}
.monthly_top_filter {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}
.monthly_top_filter button {
  padding: 8px 16px;
  border: none;
  background: #eaeaea;
  border-radius: 4px;
  cursor: pointer;
}
.monthly_top_filter button:hover {
  background: #ccc;
}
</style>
