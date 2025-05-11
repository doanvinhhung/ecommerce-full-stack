<template>
    <div class="card mb-2">
      <div class="card-header bg-white">
        <h5 class="text-center mt-2">Reviews ({{ product?.reviews?.length || 0 }})</h5>
      </div>
      <div class="card-body">
        <ul class="mt-4 list-group" v-if="product?.reviews?.length">
          <li
            class="list-group-item d-flex justify-content-between align-items-center"
            v-for="review in product.reviews"
            :key="review.id"
          >
            <div class="wsus__comment_img">
              <img :src="review.user.image_path" alt="user" class="img-fluid w-50" />
            </div>
  
            <div class="ms-2 me-auto">
              <span class="fw-bold">{{ review.title }}</span>
              <p class="card-text">{{ review.body }}</p>
              <div>
                <small class="text-body-secondary">
                  By {{ review.user.name }} - <span class="text-danger">{{ review.created_at }}</span>
                </small>
              </div>
              <div>
                <StarRating v-model:rating="review.rating" :show-rating="false" read-only :star-size="24" />
              </div>
            </div>
  
            <div
              class="d-flex flex-column align-items-center"
              v-if="authStore.isLoggedIn && authStore.user.id === review.user_id"
            >
              <button class="btn btn-sm btn-danger mb-2" @click="removeReview(review.id)">
                <i class="bi bi-trash"></i>
              </button>
              <button class="btn btn-sm btn-warning mb-2" @click="editReview(review)">
                <i class="bi bi-pencil"></i>
              </button>
            </div>
          </li>
        </ul>
        <div v-else class="text-center py-4">
          No reviews yet!
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { computed } from "vue";
  import StarRating from "vue-star-rating";
  import { useAuthStore } from "@/stores/useAuthStore";
  import { useProductDetailStore } from "@/stores/useProductsStoreDetail";
  
  // Define the stores
  const productDetailStore = useProductDetailStore();
  const authStore = useAuthStore();
  
  // Reactive product
  const product = computed(() => productDetailStore.product || {});
  
  // Functions for handling review updates and deletions
  const removeReview = (reviewId) => {
    productDetailStore.removeReview(reviewId);
  };
  
  const editReview = (review) => {
    productDetailStore.editReview(review);
  };
  </script>
  