<template>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse"
        data-bs-target="#collapseBrand" aria-expanded="true">
        Thương hiệu ({{ totalBrandProducts }})
      </button>
    </h2>
    <div id="collapseBrand" class="accordion-collapse collapse show">
      <div class="accordion-body">
        <div class="form-check" v-for="brand in brandsWithCount" :key="brand.id">
          <input class="form-check-input" type="checkbox" 
            :id="'brand-'+brand.id"
            :checked="isSelected(brand.id)"
            @change="toggleBrand(brand.id)">
          <label class="form-check-label" :for="'brand-'+brand.id">
            {{ brand.name }} ({{ brand.count }})
          </label>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'; // Import các hàm của Vue Composition API
import { useProductsStore } from '@/stores/useProductsStore'; // Import store quản lý sản phẩm
import { useRoute, useRouter } from 'vue-router'; // Import các hàm từ Vue Router để lấy route và thực hiện điều hướng

// Khởi tạo các đối tượng để truy cập thông tin về route và router
const route = useRoute(); // Truy cập thông tin của route hiện tại (URL, query params)
const router = useRouter(); // Để thực hiện điều hướng (cập nhật URL)

const productsStore = useProductsStore(); // Truy cập store để lấy thông tin về sản phẩm và các dữ liệu liên quan

// Computed property để tính toán danh sách các thương hiệu (brands) kèm theo số lượng sản phẩm của mỗi thương hiệu
const brandsWithCount = computed(() => {
  return productsStore.brands.map(brand => {
    // Đếm số lượng sản phẩm có cùng thương hiệu
    const count = productsStore.products.filter(p => 
      p.brand && p.brand.id === brand.id // So sánh thương hiệu của sản phẩm với id của thương hiệu hiện tại
    ).length;
    return { ...brand, count }; // Trả về thương hiệu với thông tin count
  }).filter(brand => brand.count > 0); // Chỉ giữ lại các thương hiệu có ít nhất 1 sản phẩm
});

// Computed property để tính tổng số sản phẩm của tất cả các thương hiệu
const totalBrandProducts = computed(() => {
  return brandsWithCount.value.reduce((total, brand) => total + brand.count, 0);
});

// Hàm kiểm tra xem thương hiệu có được chọn hay không
const isSelected = (brandId) => {
  if (!route.query.brand_id) return false; // Nếu không có tham số `brand_id` trong URL, trả về false
  return route.query.brand_id.split(',').includes(brandId.toString()); // Kiểm tra xem `brand_id` trong query có chứa thương hiệu này không
};

// Hàm để thay đổi trạng thái chọn/deselect một thương hiệu
const toggleBrand = (brandId) => {
  const query = { ...route.query }; // Sao chép các tham số query hiện tại của URL
  let currentBrands = [];
  
  // Nếu đã có tham số `brand_id` trong URL, chuyển nó thành mảng
  if (query.brand_id) {
    currentBrands = query.brand_id.split(',');
  }
  
  const index = currentBrands.indexOf(brandId.toString()); // Kiểm tra xem thương hiệu này có trong mảng không
  if (index > -1) {
    currentBrands.splice(index, 1); // Nếu có, xóa thương hiệu khỏi mảng (deselect)
  } else {
    currentBrands.push(brandId.toString()); // Nếu chưa có, thêm thương hiệu vào mảng (select)
  }
  
  // Nếu có các thương hiệu được chọn, cập nhật lại tham số `brand_id` trong query
  if (currentBrands.length > 0) {
    query.brand_id = currentBrands.join(',');
  } else {
    // Nếu không có thương hiệu nào được chọn, xóa tham số `brand_id` khỏi query
    delete query.brand_id;
  }
  
  // Điều hướng tới URL mới với các tham số query đã được cập nhật
  router.push({ query });
};
</script>
