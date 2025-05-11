import { defineStore } from "pinia"
import axios from "axios"
import { BASE_URL } from "../helpers/config"
import { useAuthStore } from "./useAuthStore"
import { useToast } from "vue-toastification"

export const useProductDetailStore = defineStore("product", {
  state: () => ({
    product: {},
    productThumbnail: "",
    productImages: [],
    isLoading: false,
    errorMessage: "",
    reviewToUpdate: {
      updating: false,
      data: null,
    },
  }),

  actions: {
    editReview(review) {
      this.reviewToUpdate = {
        updating: true,
        data: review
      }
    },
  
    cancelUpdating() {
      this.reviewToUpdate = {
        updating: false,
        data: null
      }
    },

    async storeReview(reviewData) {
      const toast = useToast()
      const authStore = useAuthStore()
      this.isLoading = true
    
      try {
        const productId = this.product.id
        if (!productId) throw new Error('Product ID is required')
    
        const response = await axios.post(`${BASE_URL}/store/review`, {
          title: reviewData.title,
          body: reviewData.body,
          rating: reviewData.rating,
          product_id: productId
        }, {
          headers: {
            'Authorization': `Bearer ${authStore.accessToken}`
          }
        })
    
        if (response.data.data) {
          const newReview = {
            ...response.data.data,
            user: authStore.user
          }
          
          this.product.reviews = this.product.reviews || []
          this.product.reviews.unshift(newReview)
          
          toast.success("Review added successfully!", {
            timeout: 3000
          })
        }
    
        return response.data
      } catch (error) {
        toast.error(error.response?.data?.message || "Failed to submit review", {
          timeout: 3000
        })
        throw error
      } finally {
        this.isLoading = false
      }
    },
  
    async removeReview(reviewId) {
      const toast = useToast()
      const authStore = useAuthStore()
      try {
        await axios.delete(`${BASE_URL}/delete/review/${reviewId}`, {
          headers: {
            Authorization: `Bearer ${authStore.accessToken}`,
          },
        })

        this.product.reviews = this.product.reviews.filter(
          (r) => r.id !== reviewId
        )
        
        toast.success("Review deleted successfully", {
          timeout: 3000
        })
      } catch (error) {
        toast.error(error.response?.data?.message || "Failed to delete review", {
          timeout: 3000
        })
        throw error
      }
    },

    async updateReview(reviewData) {
      const toast = useToast()
      const authStore = useAuthStore()
      this.isLoading = true

      try {
        const response = await axios.put(
          `${BASE_URL}/update/review/${reviewData.id}`,
          {
            title: reviewData.title,
            body: reviewData.body,
            rating: reviewData.rating,
            product_id: reviewData.product_id,
          },
          {
            headers: {
              Authorization: `Bearer ${authStore.accessToken}`,
            },
          }
        )

        const index = this.product.reviews.findIndex(
          (r) => r.id === reviewData.id
        )
        if (index !== -1) {
          this.product.reviews[index] = response.data.data
        }
        
        toast.success("Review updated successfully!", {
          timeout: 3000
        })
        
        return response.data
      } catch (error) {
        toast.error(error.response?.data?.message || "Failed to update review", {
          timeout: 3000
        })
        throw error
      } finally {
        this.isLoading = false
        this.cancelUpdating()
      }
    },
    
    async fetchProduct(slug) {
      const toast = useToast()
      try {
        this.isLoading = true
        this.errorMessage = ""

        const response = await axios.get(`${BASE_URL}/product/${slug}/show`)
        this.product = response.data.data

        this.productImages = [
          { id: 1, src: this.product.thumbnail },
          ...["first_image", "second_image", "third_image"]
            .filter((key) => this.product[key])
            .map((key, index) => ({
              id: index + 2,
              src: this.product[key],
            })),
        ]

        this.productThumbnail = this.product.thumbnail
      } catch (error) {
        this.errorMessage =
          error.response?.data?.message || "Failed to load product"
        toast.error(this.errorMessage, {
          timeout: 3000
        })
        console.error("Fetch product error:", error)
      } finally {
        this.isLoading = false
      }
    },
  },
})