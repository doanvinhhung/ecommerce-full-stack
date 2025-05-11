<template>
  <!-- products list header -->
  <div class="row">
    <div class="col-xl-12">
      <div class="wsus__section_header for_md">
        <h3>Gợi ý hôm nay</h3>
      </div>
    </div>
  </div>

  <div class="col-xl-12 col-lg-12">
    <div v-if="isLoading" class="loading-spinner">Loading...</div>

    <div v-else class="row grid">
      <ProductListItem
        v-for="product in visibleProducts"
        :key="product.id"
        :product="product"
      />
      <div v-if="isFetching" class="col-12 text-center my-3">
        <span>Đang tải thêm sản phẩm...</span>
      </div>
      <div v-if="!hasMore && visibleProducts.length > 0" class="col-12 text-center my-3 text-muted">
        <span>Đã hiển thị tất cả sản phẩm</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import ProductListItem from "./ProductListItem.vue";
import { useProductsStore } from "@/stores/useProductsStore";
import { onMounted, ref, computed, onUnmounted } from "vue";

const productsStore = useProductsStore();
const isLoading = ref(true);
const isFetching = ref(false);
const hasMore = ref(true);
const visibleCount = ref(6);
const fetchStep = 6;

const visibleProducts = computed(() =>
  productsStore.products.slice(0, visibleCount.value)
);

// Cuộn vô hạn: kiểm tra cuộn cuối trang
const handleScroll = () => {
  const bottomReached =
    window.innerHeight + window.scrollY >= document.body.offsetHeight - 100;

  if (bottomReached && !isFetching.value && hasMore.value) {
    loadMore();
  }
};

const loadMore = () => {
  if (visibleCount.value >= productsStore.products.length) {
    hasMore.value = false;
    return;
  }
  isFetching.value = true;
  setTimeout(() => {
    visibleCount.value = Math.min(
      visibleCount.value + fetchStep,
      productsStore.products.length
    );
    isFetching.value = false;
  }, 500); // Mô phỏng delay
};

onMounted(async () => {
  await productsStore.fetchAllProducts();
  isLoading.value = false;
  hasMore.value = visibleCount.value < productsStore.products.length;
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
</style>
