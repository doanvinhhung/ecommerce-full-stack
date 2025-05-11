import { defineStore } from "pinia";
import { useToast } from "vue-toastification";
import axios from 'axios';
import { BASE_URL } from '@/helpers/config';
import { useAuthStore } from "./useAuthStore";
const toast = useToast();

export const useCartStore = defineStore("cart", {
  state: () => ({
    cartItems: [],
    coupon: null,
    userCouponUsage: 0,
    discountAmount: 0
  }),
  persist: true,
  getters: {
    subtotal: (state) => {
      return state.cartItems.reduce((sum, item) => {
        return sum + (item.price * item.quantity);
      }, 0);
    },
    total: (state) => {
      const shipping = 10.00;
      return state.subtotal + shipping - state.discountAmount;
    },
    formatPrice: () => (price) => {
      return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
      }).format(price).replace('₫', '').trim() + ' VND';
    }
  },
  actions: {
    async applyCoupon(code) {
      const authStore = useAuthStore();
      
      try {
        // Validate coupon
        const response = await axios.post(`${BASE_URL}/coupons/validate`, {
          code: code.trim(),
          total_amount: this.subtotal
        });
        
        if (response.data.status === 'success') {
          // Check user usage
          const usageResponse = await axios.get(`${BASE_URL}/coupons/check-usage`, {
            params: { coupon_code: code.trim() },
            headers: { Authorization: `Bearer ${authStore.accessToken}` }
          });
    
          console.log('Coupon usage data:', usageResponse.data);
          
          if (usageResponse.data.usage_count >= usageResponse.data.max_use_per_user) {
            toast.error(`Bạn chỉ được dùng mã này ${usageResponse.data.max_use_per_user} lần`);
            return false;
          }
          
          // Apply coupon
          this.coupon = {
            code: code.trim(),
            name: response.data.coupon.name,
            discount_amount: response.data.discount_amount,
            max_use_per_user: response.data.coupon.max_use_per_user,
            current_usage: usageResponse.data.usage_count + 1
          };
          
          this.discountAmount = response.data.discount_amount;
          toast.success('Áp dụng mã giảm giá thành công!');
          return true;
        }
      } catch (error) {
        console.error('Coupon error:', error);
        let errorMsg = error.response?.data?.message || 'Mã giảm giá không hợp lệ';
        toast.error(errorMsg);
        return false;
      }
    },

    async checkCouponUsage() {
      if (!this.coupon?.code) return;
      
      try {
        const authStore = useAuthStore();
        const response = await axios.get(`${BASE_URL}/coupons/check-usage`, {
          params: { 
            coupon_code: this.coupon.code // Gửi code thay vì id
          },
          headers: { 
            Authorization: `Bearer ${authStore.accessToken}` 
          }
        });
    
        console.log('Coupon usage data:', response.data);
        
        // Đảm bảo so sánh đúng coupon code (string)
        if (response.data.coupon_code === this.coupon.code) {
          this.userCouponUsage = response.data.usage_count;
        }
      } catch (error) {
        console.error('Lỗi khi kiểm tra coupon usage:', error);
      }
    },
    
    removeCoupon() {
      this.coupon = null;
      this.discountAmount = 0;
    },

    addToCart(item) {
      let index = this.cartItems.findIndex(product => 
        product.product_id === item.product_id &&
        product.color === item.color && 
        product.size === item.size
      );
    
      if (index !== -1) {
        if (this.cartItems[index].quantity + item.quantity > item.maxQuantity) {
          toast.warning(`Số lượng tối đa là ${item.maxQuantity}`, {
            timeout: 2000,
          });
          return;
        } else {
          this.cartItems[index].quantity += item.quantity;
          toast.success("Sản phẩm đã được thêm vào giỏ hàng", {
            timeout: 2000,
          });
        }
      } else {
        if (item.quantity > item.maxQuantity) {
          toast.warning(`Số lượng tối đa là ${item.maxQuantity}`, {
            timeout: 2000,
          });
          return;
        }
        this.cartItems.push(item);
        toast.success("Sản phẩm đã được thêm vào giỏ hàng", {
          timeout: 2000,
        });
      }
    },
    
    incrementQty(item) {
      let index = this.cartItems.findIndex(product => 
        product.product_id === item.product_id && 
        product.color === item.color && 
        product.size === item.size
      );
      
      if (index !== -1) {
        if (this.cartItems[index].quantity == item.maxQuantity) {
          toast.success(`Sản phẩm chỉ còn ${item.quantity} trong kho`, {
            timeout: 2000,
          });
        } else {
          this.cartItems[index].quantity += 1;
        }
      }
    },
    
    decrementQty(item) {
      let index = this.cartItems.findIndex(product => 
        product.product_id === item.product_id && 
        product.color === item.color && 
        product.size === item.size
      );
      
      if (index !== -1) {
        this.cartItems[index].quantity -= 1;
        if (this.cartItems[index].quantity === 0) {
          this.cartItems = this.cartItems.filter(product => product.ref !== item.ref);
        }
      }
    },
    
    removeFormCart(item) {
      this.cartItems = this.cartItems.filter(product => product.ref !== item.ref);
      toast.success('Sản phẩm đã bị xóa khỏi giỏ hàng', {
        timeout: 2000,
      });
    },
    
    clearCart() {
      this.cartItems = [];
    }
  },
});