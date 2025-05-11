<template>
    <div class="card mb-2">
        <!-- Show the spinner while loading -->
        <Spinner v-if="productDetailStore.isLoading" />
        
        <div class="card-header bg-white">
            <h5 class="text-center mt-2">Edit your review</h5>
        </div>

        <div class="card-body">
            <form @submit.prevent="editReview" class="mt-5 col-md-10 mx-auto">
                <!-- Title input -->
                <div class="mb-3">
                    <label for="title" class="form-label">Title*</label>
                    <input
                        type="text"
                        class="form-control"
                        name="title"
                        id="title"
                        :required="true"
                        v-model="data.review.title"
                        aria-describedby="helpId"
                        placeholder="Title"
                    />
                </div>

                <!-- Body input -->
                <div class="mb-3">
                    <label for="body" class="form-label">Review*</label>
                    <textarea
                        class="form-control"
                        :required="true"
                        v-model="data.review.body"
                        name="body"
                        id="body"
                        rows="3"
                    ></textarea>
                </div>

                <!-- Rating input -->
                <div class="mb-3">
                    <StarRating
                        v-model:rating="data.review.rating"
                        :show-rating="false"
                    />
                </div>

                <!-- Buttons -->
                <div class="mb-3">
                    <button
                        type="submit"
                        class="btn btn-dark btn-sm"
                        :disabled="data.review.rating === 0"
                    >
                        Update
                    </button>
                    <button
                        type="button"
                        class="btn btn-danger mx-2 btn-sm"
                        @click="cancelEditing"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { reactive } from "vue";
import StarRating from "vue-star-rating";
import Spinner from "../layouts/Spinner.vue";
import { useProductDetailStore } from "@/stores/useProductsStoreDetail";

// Initialize the product detail store
const productDetailStore = useProductDetailStore();

// Reactive state for review data
const data = reactive({
  review: {
    title: productDetailStore.reviewToUpdate.data.title,
    body: productDetailStore.reviewToUpdate.data.body,
    rating: productDetailStore.reviewToUpdate.data.rating,
    product_id: productDetailStore.product?.id, // Ensure product_id is passed
    id: productDetailStore.reviewToUpdate.data.id,
  },
});

// Handle review submission (edit)
const editReview = async () => {
  try {
    // Call the store action to update the review
    await productDetailStore.updateReview(data.review);
    // Optionally, reset form or notify user (if needed)
  } catch (error) {
    console.error("Failed to update review:", error);
  }
};

// Handle canceling the editing action
const cancelEditing = () => {
  productDetailStore.cancelUpdating();
};
</script>

<style scoped>
</style>
