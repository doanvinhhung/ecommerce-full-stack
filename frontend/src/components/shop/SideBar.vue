<template>
  <div class="col-xl-3 col-lg-4">
    <div class="wsus__sidebar_filter">
      <p>Bộ lọc</p>
      <span class="wsus__filter_icon">
        <i class="far fa-minus" id="minus"></i>
        <i class="far fa-plus" id="plus"></i>
      </span>
    </div>
    
    <div class="wsus__product_sidebar">
      <div class="accordion" id="accordionExample">
        <!-- <SearchForm /> -->
        <Category />
        <Brand />
        <Price />
        <Color />
        <Size />
      </div>
    </div>
  </div>
</template>

<script setup>
import SearchForm from './filter/SearchForm.vue';
import Category from './filter/Category.vue';
import Brand from './filter/Brand.vue';
import Price from './filter/Price.vue';
import Color from './filter/Color.vue';
import Size from './filter/Size.vue';
import { onMounted } from 'vue';


onMounted(() => {
  // Đảm bảo jQuery đã được tải và có sẵn
  if (typeof $ !== 'undefined') {
    // Khởi tạo lại slick slider khi trang được tải
       // ======SIDEBAR FILTER======  
       $('.wsus__sidebar_filter').on('click', function () {
        $('.wsus__product_sidebar').toggleClass('show_filter');
    });

    $('.wsus__sidebar_filter').on('click', function () {
        $('#plus').toggleClass('show_plus');
    });
  }
});
</script>