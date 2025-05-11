<template>
  <!--==========================
    PRODUCT  REPORT MODAL VIEW
    ===========================-->
  <section class="product_popup_modal report_modal">
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Report Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                class="far fa-times"></i></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <form>
                  <div class="wsus__single_input">
                    <label>Subject</label>
                    <input type="text" placeholder="Type Subject">
                  </div>
                  <div class="wsus__single_input">
                    <label>email</label>
                    <input type="email" placeholder="Type Email">
                  </div>
                  <div class="wsus__single_input">
                    <label>Description</label>
                    <textarea cols="3" rows="3" placeholder="Brief description of your issue"></textarea>
                  </div>
                  <div class="wsus__report_img">
                    <div class="img_upload">
                      <div class="gallery">
                        <a class="cam" href="javascript:void(0)"><span><i class="fas fa-image"></i></span></a>
                      </div>
                    </div>
                  </div>

                  <button type="submit" class="common_btn">submit</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--==========================
    PRODUCT REPORT MODAL VIEW 
    ===========================-->

  <!--==========================
      PRODUCT MODAL VIEW START
    ===========================-->
  <!-- <section class="product_popup_modal">
        <div class="modal fade" id="exampleModal2" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="far fa-times"></i></button>
                        <div class="row">
                            <div class="col-xl-6 col-12 col-sm-10 col-md-8 col-lg-6 m-auto display">
                                <div class="wsus__quick_view_img">
                                    <a class="venobox wsus__pro_det_video" data-autoplay="true" data-vbtype="video"
                                        href="https://youtu.be/7m16dFI1AF8">
                                        <i class="fas fa-play"></i>
                                    </a>
                                    <div class="row modal_slider">
                                        <div class="col-xl-12">
                                            <div class="modal_slider_img">
                                                <img src="/assets/images/zoom1.jpg" alt="product" class="img-fluid w-100">
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="modal_slider_img">
                                                <img src="/assets/images/zoom2.jpg" alt="product" class="img-fluid w-100">
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="modal_slider_img">
                                                <img src="/assets/images/zoom3.jpg" alt="product" class="img-fluid w-100">
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="modal_slider_img">
                                                <img src="/assets/images/zoom4.jpg" alt="product" class="img-fluid w-100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-12 col-sm-12 col-md-12 col-lg-6">
                                <div class="wsus__pro_details_text">
                                    <a class="title" href="#">Electronics Black Wrist Watch</a>
                                    <p class="wsus__stock_area"><span class="in_stock">in stock</span> (167 item)</p>
                                    <h4>$50.00 <del>$60.00</del></h4>
                                    <p class="review">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                        <span>20 review</span>
                                    </p>
                                    <p class="description">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>

                                    <div class="wsus_pro_hot_deals">
                                        <h5>offer ending time : </h5>
                                        <div class="simply-countdown simply-countdown-one"></div>
                                    </div>
                                    <div class="wsus_pro_det_color">
                                        <h5>color :</h5>
                                        <ul>
                                            <li><a class="blue" href="#"><i class="far fa-check"></i></a></li>
                                            <li><a class="orange" href="#"><i class="far fa-check"></i></a></li>
                                            <li><a class="yellow" href="#"><i class="far fa-check"></i></a></li>
                                            <li><a class="black" href="#"><i class="far fa-check"></i></a></li>
                                            <li><a class="red" href="#"><i class="far fa-check"></i></a></li>
                                        </ul>
                                    </div>
                                    <div class="wsus_pro__det_size">
                                        <h5>size :</h5>
                                        <ul>
                                            <li><a href="#">S</a></li>
                                            <li><a href="#">M</a></li>
                                            <li><a href="#">L</a></li>
                                            <li><a href="#">XL</a></li>
                                        </ul>
                                    </div>
                                    <div class="wsus__quentity">
                                        <h5>quentity :</h5>
                                        <form class="select_number">
                                            <input class="number_area" type="text" min="1" max="100" value="1" />
                                        </form>
                                        <h3>$50.00</h3>
                                    </div>
                                    <div class="wsus__selectbox">
                                        <div class="row">
                                            <div class="col-xl-6 col-sm-6">
                                                <h5 class="mb-2">select:</h5>
                                                <select class="select_2" name="state">
                                                    <option>default select</option>
                                                    <option>select 1</option>
                                                    <option>select 2</option>
                                                    <option>select 3</option>
                                                    <option>select 4</option>
                                                </select>
                                            </div>
                                            <div class="col-xl-6 col-sm-6">
                                                <h5 class="mb-2">select:</h5>
                                                <select class="select_2" name="state">
                                                    <option>default select</option>
                                                    <option>select 1</option>
                                                    <option>select 2</option>
                                                    <option>select 3</option>
                                                    <option>select 4</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="wsus__button_area">
                                        <li><a class="add_cart" href="#">add to cart</a></li>
                                        <li><a class="buy_now" href="#">buy now</a></li>
                                        <li><a href="#"><i class="fal fa-heart"></i></a></li>
                                        <li><a href="#"><i class="far fa-random"></i></a></li>
                                    </ul>
                                    <p class="brand_model"><span>model :</span> 12345670</p>
                                    <p class="brand_model"><span>brand :</span> The Northland</p>
                                    <div class="wsus__pro_det_share">
                                        <h5>share :</h5>
                                        <ul class="d-flex">
                                            <li><a class="facebook" href="#"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a class="twitter" href="#"><i class="fab fa-twitter"></i></a></li>
                                            <li><a class="whatsapp" href="#"><i class="fab fa-whatsapp"></i></a></li>
                                            <li><a class="instagram" href="#"><i class="fab fa-instagram"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
  <!--==========================
      PRODUCT MODAL VIEW END
    ===========================-->


  <!--============================
        BREADCRUMB START
    ==============================-->
  <section id="wsus__breadcrumb">
    <div class="wsus_breadcrumb_overlay">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <h4>products details</h4>
            <ul>
              <li><a href="#">home</a></li>
              <li><a href="#">peoduct</a></li>
              <li><a href="#">product details</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--============================
        BREADCRUMB END
    ==============================-->
  <div v-if="isLoading" class="loading-spinner">Loading...</div>

  <Spinner :store="productDetailStore"></Spinner>
  <!--============================
        PRODUCT DETAILS START
    ==============================-->
  <section id="wsus__product_details">
    <div class="container">
      <div class="wsus__details_bg">
        <div class="row">
          <!-- Image Gallery Section -->
          <div class="col-xl-4 col-md-5 col-lg-5">
            <vue-image-zoomer :regular="selectedImage" :zoom="selectedImage" :zoom-amount="3" img-class="img-fluid"
              alt="Product Image" />

            <div class="wsus__pro_img_thumb mt-3">
              <div class="row">
                <div v-for="productImage in productDetailStore.productImages" :key="productImage.id" class="col-3">
                  <img :src="productImage.src" :alt="productImage.alt || 'Product thumbnail'"
                    class="img-fluid rounded thumbnail" :class="{ 'active': productImage.src === selectedImage }"
                    @click="changeMainImage(productImage.src)" />
                </div>
              </div>
            </div>
          </div>

          <!-- Product Details Section -->
          <div class="col-xl-5 col-md-7 col-lg-7">
            <div class="wsus__pro_details_text">
              <h1 class="title">{{ productDetailStore.product?.name }}</h1>

              <p class="wsus__stock_area">
                <span :class="isProductInStock ? 'in_stock' : 'out_stock'">
                  {{ isProductInStock ? 'in stock' : 'out stock' }}
                </span>
                ({{ productDetailStore.product?.quantity || 0 }} items)
              </p>

              <h4>
                ${{ productDetailStore.product?.price?.toFixed(2) || '0.00' }}
                <del>$60.00</del>
              </h4>

              <StarRating v-model:rating="reviewAvg" :show-rating="false" read-only :star-size="24" />

              <!-- Color Selection -->
              <div class="wsus_pro_det_color" v-if="productDetailStore.product?.colors?.length">
                <h5>Color:</h5>
                <ul>
                  <li v-for="color in productDetailStore.product.colors" :key="color.id">
                    <a href="#" @click.prevent="setChosenColor(color)" :class="[
                      color.name,
                      {
                        selected: data.chosenColor?.id === color.id
                      }
                    ]">
                      <i v-if="data.chosenColor?.id === color.id" class="far fa-check"></i>
                    </a>
                  </li>
                </ul>
              </div>

              <!-- Size Selection -->
              <div class="wsus_pro__det_size" v-if="productDetailStore.product?.sizes?.length">
                <h5>Size:</h5>
                <ul>
                  <li v-for="size in productDetailStore.product.sizes" :key="size.id">
                    <a href="#" @click.prevent="setChosenSize(size)" :class="[
                      size.name,
                      {
                        selected: data.chosenSize?.id === size.id
                      }
                    ]">
                      {{ size.name }}
                    </a>
                  </li>
                </ul>
              </div>

              <!-- Quantity Selector -->
              <div class="wsus__quantity d-flex align-items-center">
                <h5 class="mr-5 font-weight-bold text-dark">Quantity:</h5>
                <input class="form-control form-control-lg w-25 rounded-pill border-primary" type="number" min="1"
                  :max="productDetailStore.product?.quantity" v-model.number="data.quantity" />
              </div>

              <!-- Action Buttons -->
              <ul class="wsus__button_area">
                <li>
                  <button class="add_cart" :disabled="isAddToCartDisabled" @click="addToCart">
                    add to cart
                  </button>
                </li>
                <li><a class="buy_now" href="#">buy now</a></li>
                <li><a href="#"><i class="fal fa-heart"></i></a></li>
                <li><a href="#"><i class="far fa-random"></i></a></li>
              </ul>

              <!-- Product Metadata -->
              <p class="brand_model">
                <span>model:</span> {{ productDetailStore.product?.id }}
              </p>
              <p class="brand_model">
                <span>brand:</span> {{ productDetailStore.product?.brand?.name || 'N/A' }}
              </p>

              <!-- Social Sharing -->
              <div class="wsus__pro_det_share">
                <h5>share:</h5>
                <ul class="d-flex">
                  <li><a class="facebook" href="#"><i class="fab fa-facebook-f"></i></a></li>
                  <li><a class="twitter" href="#"><i class="fab fa-twitter"></i></a></li>
                  <li><a class="whatsapp" href="#"><i class="fab fa-whatsapp"></i></a></li>
                  <li><a class="instagram" href="#"><i class="fab fa-instagram"></i></a></li>
                </ul>
              </div>

              <a class="wsus__pro_report" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fal fa-comment-alt-smile"></i> Report incorrect product information.
              </a>
            </div>
          </div>

          <!-- Sidebar Section -->
          <div class="col-xl-3 col-md-12 mt-md-5 mt-lg-0">
            <div class="wsus_pro_det_sidebar" id="sticky_sidebar">
              <ul>
                <li>
                  <span><i class="fal fa-truck"></i></span>
                  <div class="text">
                    <h4>Return Available</h4>
                  </div>
                </li>
                <li>
                  <span><i class="far fa-shield-check"></i></span>
                  <div class="text">
                    <h4>Secure Payment</h4>
                  </div>
                </li>
                <li>
                  <span><i class="fal fa-envelope-open-dollar"></i></span>
                  <div class="text">
                    <h4>Warranty Available</h4>
                  </div>
                </li>
              </ul>

              <div class="wsus__det_sidebar_banner">
                <img src="/assets/images/blog_1.jpg" alt="Special offer" class="img-fluid w-100" />
                <div class="wsus__det_sidebar_banner_text_overlay">
                  <div class="wsus__det_sidebar_banner_text">
                    <p>Black Friday Sale</p>
                    <h4>Up To 70% Off</h4>
                    <a href="#" class="common_btn">shop now</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xl-12">
          <div class="wsus__pro_det_description">
            <div class="wsus__details_bg">
              <ul class="nav nav-pills mb-3" id="pills-tab3" role="tablist">

                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="pills-contact-tab2" data-bs-toggle="pill"
                    data-bs-target="#pills-contact2" type="button" role="tab" aria-controls="pills-contact2"
                    aria-selected="false">Reviews</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="pills-home-tab7" data-bs-toggle="pill"
                    data-bs-target="#pills-home22" type="button" role="tab" aria-controls="pills-home"
                    aria-selected="true">Description</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="pills-profile-tab7" data-bs-toggle="pill"
                    data-bs-target="#pills-profile22" type="button" role="tab" aria-controls="pills-profile"
                    aria-selected="false">Information</button>
                </li>
              </ul>
              <!-- Reviews Tab -->

<!-- Thay thế phần tab content hiện tại bằng code này -->
<div class="tab-content" id="pills-tab3Content">
  <!-- Reviews Tab -->
  <div class="tab-pane fade" id="pills-contact2" role="tabpanel" aria-labelledby="pills-contact-tab2">
    <ReviewList v-if="productDetailStore.product" />
    <Spinner v-else />

    <!-- Conditionally render based on login and purchase status -->
    <div v-if="authStore.isLoggedIn">
      <div v-if="checkIfUserBoughtTheProduct()">
        <UpdateReview v-if="productDetailStore.reviewToUpdate.updating" />
        <AddReview v-else />
      </div>
    </div>
  </div>

  <!-- Description Tab -->
  <div class="tab-pane fade show active" id="pills-home22" role="tabpanel" aria-labelledby="pills-home-tab7">
    <div class="product-description">
      <div class="description-content" v-html="productDetailStore.product?.description"></div>
      
      <div class="features-grid mt-4">
        <div class="row g-4">
          <div class="col-xl-4 col-md-6">
            <div class="feature-card">
              <div class="feature-icon">
                <i class="fas fa-truck"></i>
              </div>
              <div class="feature-text">
                <h5>Free Shipping & Return</h5>
                <p>We offer free shipping for products on orders above $50 and free delivery for all orders in US.</p>
              </div>
            </div>
          </div>
          
          <div class="col-xl-4 col-md-6">
            <div class="feature-card">
              <div class="feature-icon">
                <i class="fas fa-sync-alt"></i>
              </div>
              <div class="feature-text">
                <h5>Free and Easy Returns</h5>
                <p>We guarantee our products and you could get back all of your money anytime you want in 30 days.</p>
              </div>
            </div>
          </div>
          
          <div class="col-xl-4 col-md-6">
            <div class="feature-card">
              <div class="feature-icon">
                <i class="fas fa-credit-card"></i>
              </div>
              <div class="feature-text">
                <h5>Special Financing</h5>
                <p>Get 20%-50% off items over $50 for a month or over $250 for a year with our special credit card.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Information Tab -->
  <div class="tab-pane fade" id="pills-profile22" role="tabpanel" aria-labelledby="pills-profile-tab7">
    <div class="product-specs">
      <div class="row">
        <div class="col-lg-6">
          <div class="specs-card">
            <h4 class="specs-title">Product Specifications</h4>
            <ul class="specs-list">
              <li v-if="productDetailStore.product?.weight">
                <span class="spec-name">Weight:</span>
                <span class="spec-value">{{ productDetailStore.product.weight }} kg</span>
              </li>
              <li v-if="productDetailStore.product?.dimensions">
                <span class="spec-name">Dimensions:</span>
                <span class="spec-value">{{ productDetailStore.product.dimensions }}</span>
              </li>
              <li v-if="productDetailStore.product?.materials">
                <span class="spec-name">Materials:</span>
                <span class="spec-value">{{ productDetailStore.product.materials }}</span>
              </li>
              <li v-if="productDetailStore.product?.colors?.length">
                <span class="spec-name">Available Colors:</span>
                <span class="spec-value">
                  <span v-for="(color, index) in productDetailStore.product.colors" :key="color.id">
                    {{ color.name }}<span v-if="index < productDetailStore.product.colors.length - 1">, </span>
                  </span>
                </span>
              </li>
            </ul>
          </div>
        </div>
        
        <div class="col-lg-6">
          <div class="specs-card">
            <h4 class="specs-title">Shipping & Policies</h4>
            <ul class="specs-list">
              <li>
                <span class="spec-name">Shipping Method:</span>
                <span class="spec-value">Standard, Express</span>
              </li>
              <li>
                <span class="spec-name">Delivery Time:</span>
                <span class="spec-value">3-5 business days</span>
              </li>
              <li>
                <span class="spec-name">Return Policy:</span>
                <span class="spec-value">30 days money back guarantee</span>
              </li>
              <li>
                <span class="spec-name">Warranty:</span>
                <span class="spec-value">1 year manufacturer warranty</span>
              </li>
            </ul>
          </div>
          
          <div class="supplier-info mt-4" v-if="productDetailStore.product?.supplier">
            <h4 class="specs-title">Supplier Information</h4>
            <div class="supplier-card">
              <div class="supplier-header">
                <img :src="productDetailStore.product.supplier.logo || '/images/default-supplier.png'" 
                     alt="Supplier logo" 
                     class="supplier-logo">
                <h5 class="supplier-name">{{ productDetailStore.product.supplier.name }}</h5>
              </div>
              <div class="supplier-details">
                <p v-if="productDetailStore.product.supplier.description">
                  {{ productDetailStore.product.supplier.description }}
                </p>
                <div class="supplier-contact">
                  <div v-if="productDetailStore.product.supplier.email">
                    <i class="fas fa-envelope"></i>
                    {{ productDetailStore.product.supplier.email }}
                  </div>
                  <div v-if="productDetailStore.product.supplier.phone">
                    <i class="fas fa-phone"></i>
                    {{ productDetailStore.product.supplier.phone }}
                  </div>
                  <div v-if="productDetailStore.product.supplier.address">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ productDetailStore.product.supplier.address }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>
  <!--============================
        PRODUCT DETAILS END
    ==============================-->

</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { useProductDetailStore } from '@/stores/useProductsStoreDetail'
import { useCartStore } from '@/stores/useCartStore'
import { makeUniqueId } from '@/helpers/config'
import Spinner from '../layouts/Spinner.vue'
import StarRating from "vue-star-rating"
import AddReview from '../reviews/AddReview.vue'
import { useAuthStore } from '@/stores/useAuthStore'
import ReviewList from '../reviews/ReviewList.vue'
import { useOrderListStore } from '@/stores/useOrderListStore'
import UpdateReview from '../reviews/UpdateReview.vue'

const orderListStore = useOrderListStore();
const productDetailStore = useProductDetailStore();
const authStore = useAuthStore();
const cartStore = useCartStore();

const props = defineProps({
  slug: {
    type: String,
    required: true
  }
})

const isLoading = ref(true)

const data = reactive({
  quantity: 1,
  chosenColor: null,
  chosenSize: null
})

// Computed properties
const selectedImage = computed(() => {
  return productDetailStore.product?.thumbnail ||
    productDetailStore.productImages[0]?.src ||
    '/placeholder-image.jpg'
})

const isProductInStock = computed(() => {
  return productDetailStore.product?.status && productDetailStore.product?.quantity > 0
})

const isAddToCartDisabled = computed(() => {
  return !data.chosenColor || !data.chosenSize || !isProductInStock.value
})

const reviewAvg = computed(() => {
  const reviews = productDetailStore.product?.reviews || [];
  return reviews.length > 0 
    ? reviews.reduce((acc, review) => acc + review.rating, 0) / reviews.length
    : 0;
});

// Methods
const setChosenColor = (color) => {
  data.chosenColor = color
}

const setChosenSize = (size) => {
  data.chosenSize = size
}

const changeMainImage = (newImage) => {
  if (productDetailStore.product) {
    productDetailStore.product.thumbnail = newImage
  }
}

const addToCart = () => {
  if (isAddToCartDisabled.value) return

  const cartItem = {
    ref: makeUniqueId(10),
    product_id: productDetailStore.product?.id,
    name: productDetailStore.product?.name,
    slug: productDetailStore.product?.slug,
    quantity: data.quantity,
    price: productDetailStore.product?.price,
    color: data.chosenColor?.name,
    size: data.chosenSize?.name,
    maxQuantity: productDetailStore.product?.quantity,
    image: productDetailStore.product?.thumbnail,
    coupon_id: null,
  }

  cartStore.addToCart(cartItem)
}

const checkIfUserBoughtTheProduct = () => {
  if (!productDetailStore.product?.id) return false;
  return orderListStore.orders.some(order =>
    order.products?.some(item => item.id === productDetailStore.product.id))
};

// Lifecycle hooks
onMounted(async () => {
  if (authStore.accessToken && !authStore.user) {
    try {
      await authStore.fetchCurrentUser();
    } catch (error) {
      console.error("Error fetching user:", error);
    }
  }
  
  try {
    await Promise.all([
      orderListStore.fetchOrders(),
      productDetailStore.fetchProduct(props.slug)
    ]);
  } catch (error) {
    console.error("Error loading data:", error);
  } finally {
    isLoading.value = false;
  }
});
</script>



<style scoped>
/* Product Description Styles */
.product-description {
  padding: 20px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.description-content {
  line-height: 1.8;
  color: #555;
}

.description-content h1,
.description-content h2,
.description-content h3,
.description-content h4,
.description-content h5,
.description-content h6 {
  color: #333;
  margin-top: 1.5rem;
  margin-bottom: 1rem;
}

.description-content p {
  margin-bottom: 1rem;
}

.description-content ul,
.description-content ol {
  padding-left: 2rem;
  margin-bottom: 1.5rem;
}

.description-content img {
  max-width: 100%;
  height: auto;
  border-radius: 6px;
  margin: 1rem 0;
}

/* Feature Cards */
.feature-card {
  background: #f9f9f9;
  border-radius: 8px;
  padding: 25px;
  height: 100%;
  transition: all 0.3s ease;
  border: 1px solid #eee;
}

.feature-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.feature-icon {
  font-size: 2rem;
  color: var(--primary-color);
  margin-bottom: 15px;
}

.feature-text h5 {
  font-weight: 600;
  margin-bottom: 10px;
  color: #333;
}

.feature-text p {
  color: #666;
  margin-bottom: 0;
}

/* Product Specifications */
.product-specs {
  padding: 20px;
}

.specs-card {
  background: #fff;
  border-radius: 8px;
  padding: 25px;
  margin-bottom: 20px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.specs-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
  color: #333;
  padding-bottom: 10px;
  border-bottom: 1px solid #eee;
}

.specs-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.specs-list li {
  padding: 8px 0;
  border-bottom: 1px dashed #eee;
  display: flex;
}

.specs-list li:last-child {
  border-bottom: none;
}

.spec-name {
  font-weight: 500;
  color: #555;
  min-width: 160px;
}

.spec-value {
  color: #333;
}

/* Supplier Information */
.supplier-info {
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
}

.supplier-card {
  padding: 20px;
}

.supplier-header {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
}

.supplier-logo {
  width: 60px;
  height: 60px;
  object-fit: contain;
  border-radius: 50%;
  border: 1px solid #eee;
  margin-right: 15px;
}

.supplier-name {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 0;
  color: #333;
}

.supplier-details p {
  color: #666;
  line-height: 1.7;
  margin-bottom: 15px;
}

.supplier-contact div {
  margin-bottom: 8px;
  color: #555;
  display: flex;
  align-items: center;
}

.supplier-contact i {
  margin-right: 10px;
  color: var(--primary-color);
  width: 20px;
  text-align: center;
}

/* Responsive Adjustments */
@media (max-width: 767px) {
  .specs-list li {
    flex-direction: column;
  }
  
  .spec-name {
    margin-bottom: 5px;
    min-width: auto;
  }
  
  .feature-card {
    padding: 20px;
  }
  
  .supplier-header {
    flex-direction: column;
    text-align: center;
  }
  
  .supplier-logo {
    margin-right: 0;
    margin-bottom: 15px;
  }
}
</style>