<template>
  <section id="wsus__product_page">
    <div class="container">
      <div class="row">

        <ActiveFilters />
           <SideBar></SideBar>
        

        <div class="col-xl-9 col-lg-8">
          <div class="row">
            <div class="col-xl-12 d-none d-md-block mt-md-4 mt-lg-0">
              <div class="wsus__product_topbar">
                <div class="wsus__product_topbar_left">
                  <div class="nav nav-pills" id="v-pills-tab" role="tablist">
                    <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                      data-bs-target="#v-pills-home" type="button" role="tab"
                      aria-controls="v-pills-home" aria-selected="true">
                      <i class="fas fa-th"></i>
                    </button>
                  </div>
                </div>
               
                <div class="wsus__topbar_select">
                  <select class="" v-model="sortOption" @change="handleSort">
                    <option value="newest">Mới nhất</option>
                    <option value="price_asc">Giá tăng dần</option>
                    <option value="price_desc">Giá giảm dần</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel">
              <div v-if="isLoading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
              
              <div v-else-if="!hasProducts" class="text-center py-5">
                <h5>Không tìm thấy sản phẩm phù hợp</h5>
              </div>
              
              <div v-else class="row">
                <div class="col-xl-3 col-sm-6 col-6 col-md-4 col-lg-4" v-for="product in products" :key="product.id">
                  <ShopListItem :product="product" />
                </div>
              </div>

              <div class="row mt-4" v-if="pagination.totalPages > 1">
                <div class="col-12">
                  <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                      <li class="page-item" :class="{ disabled: pagination.currentPage === 1 }">
                        <button class="page-link" @click="changePage(pagination.currentPage - 1)">
                          &laquo;
                        </button>
                      </li>
                      
                      <li class="page-item" v-for="page in pagination.totalPages" :key="page"
                        :class="{ active: pagination.currentPage === page }">
                        <button class="page-link" @click="changePage(page)">
                          {{ page }}
                        </button>
                      </li>
                      
                      <li class="page-item" :class="{ disabled: pagination.currentPage === pagination.totalPages }">
                        <button class="page-link" @click="changePage(pagination.currentPage + 1)">
                          &raquo;
                        </button>
                      </li>
                    </ul>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, ref, watch, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useProductsStore } from '@/stores/useProductsStore';
import ShopListItem from './ShopListItem.vue';
import ActiveFilters from './filter/ActiveFilters.vue';

import SideBar from './SideBar.vue';

// Khởi tạo các đối tượng từ Vue Router và Pinia store
const route = useRoute(); // Lấy thông tin route hiện tại (URL và query params)
const router = useRouter(); // Dùng để điều hướng giữa các trang (thay đổi URL)
const productsStore = useProductsStore(); // Truy cập store để lấy và xử lý dữ liệu sản phẩm

// Khởi tạo biến để quản lý tùy chọn sắp xếp (sort) với giá trị mặc định từ query params hoặc 'newest' nếu không có
const sortOption = ref(route.query.sort || 'newest');

// Các computed property để lấy dữ liệu từ store
const isLoading = computed(() => productsStore.isLoading); // Trạng thái loading (đang tải) từ store
const products = computed(() => productsStore.products); // Danh sách các sản phẩm hiện tại từ store
const hasProducts = computed(() => productsStore.hasProducts); // Kiểm tra xem có sản phẩm hay không
const pagination = computed(() => productsStore.paginationInfo); // Thông tin phân trang từ store

// Hàm để áp dụng bộ lọc từ query params của URL
const applyFiltersFromURL = () => {
  const filters = { ...route.query, sort: route.query.sort || 'newest' }; // Lấy các tham số từ URL và thêm tùy chọn sắp xếp
  // Gọi hàm fetchFilteredProducts trong store để lấy sản phẩm theo bộ lọc và trang
  productsStore.fetchFilteredProducts(filters, route.query.page || 1);
};

// Hook mounted để áp dụng bộ lọc khi component được render lần đầu
onMounted(() => applyFiltersFromURL());

// Watcher theo dõi sự thay đổi của route.query (thay đổi URL) để cập nhật lại bộ lọc và sắp xếp
watch(() => route.query, (newQuery, oldQuery) => {
  // Nếu tham số sort thay đổi, cập nhật lại giá trị sortOption
  if (newQuery.sort !== oldQuery?.sort) {
    sortOption.value = newQuery.sort || 'newest'; // Cập nhật sortOption từ URL
  }
  // Gọi lại hàm applyFiltersFromURL khi query thay đổi
  applyFiltersFromURL();
}, { immediate: true, deep: true }); // Đặt deep: true để theo dõi mọi sự thay đổi sâu trong route.query

// Hàm xử lý khi người dùng thay đổi tùy chọn sắp xếp
const handleSort = () => {
  // Điều hướng đến URL mới với query mới bao gồm tùy chọn sort và bỏ qua page (trang sẽ được tính lại)
  router.push({ query: { ...route.query, sort: sortOption.value, page: undefined } });
};

// Hàm thay đổi trang khi người dùng chuyển trang
const changePage = (page) => {
  // Điều hướng đến URL với tham số page mới, nếu page > 1 thì mới cập nhật
  router.push({ query: { ...route.query, page: page > 1 ? page : undefined } });
};
</script>
