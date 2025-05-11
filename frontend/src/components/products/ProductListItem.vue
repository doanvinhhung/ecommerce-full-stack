<template>
  <div class="col-xl-2  col-sm-6 col-6 col-md-4 col-lg-4">
                                        <div class="wsus__product_item">
                                            <span class="wsus__new">New</span>
                                            <span class="wsus__minus">-20%</span>
                                            <router-link class="wsus__pro_link" :to="`/product/${product.slug}`">
                                                <img :src="product.thumbnail" alt="product"
                                                    class="img-fluid w-100 img_1" />
                                                <img :src="product.first_image" alt="product"
                                                    class="img-fluid w-100 img_2" />
                                            </router-link>
                                            <ul class="wsus__single_pro_icon">
                                                <li><a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                                            class="far fa-eye"></i></a></li>
                                                <li><a href="#"><i class="far fa-heart"></i></a></li>
                                                <li><a href="#"><i class="far fa-random"></i></a> </li>
                                            </ul>
                                            <div class="wsus__product_details">
                                                <router-link class="wsus__category" :to="`/product/${product.slug}`"></router-link>
                                                <p class="wsus__pro_rating">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                    <span>(17 review)</span>
                                                </p>
                                                <a class="wsus__pro_name" href="#">{{ product.name }}</a>
                                                <p class="wsus__price">${{ product.price }} <del>$200</del></p>
                                                <a class="add_cart" href="#">add to cart</a>
                                            </div>
                                        </div>
                                    </div>
</template>

  
  <script setup>
  defineProps({
    product: {
      type: Object,
      required: true,
    },
  });
  </script>
  <style scoped>

/* 
.wsus__hot_deals__single {
  position: relative;
  display: block;
  background: #fff;
  border-radius: 10px;
  padding: 15px;
  transition: all 0.3s ease-in-out;
}

.wsus__hot_deals__single:hover {
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  transform: translateY(-5px);
}


.product-labels {
  position: absolute;
  top: 8px;
  left: 8px;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.label-new,
.label-sale {
  padding: 1px 8px;
  font-size: 7px;
  font-weight: bold;
  color: white;
  border-radius: 12px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.label-new {
  background: #ff4b5c;
}

.label-sale {
  background: #ff9800;
}



.product-image {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 10px;
}


.wsus__hot_deals__single_img {
  min-height: 170px; 
  max-height: 170px; 
  overflow: hidden;
}

.wsus__hot_deals__single_img img {
  width: 100%;
  object-fit: cover;
  height: 100%;
}
 */


</style>