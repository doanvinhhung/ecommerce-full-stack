// stores/usePaymentStore.js
import { defineStore } from 'pinia';
import { useToast } from 'vue-toastification';
import axios from 'axios';
import { BASE_URL } from '@/helpers/config';
import { useRouter } from 'vue-router';
import { useCartStore } from './useCartStore';
import { useAuthStore } from './useAuthStore';

export const usePaymentStore = defineStore('payment', {
  state: () => ({
    paymentMethod: 'cod',
    isProcessing: false,
    paymentMethods: [
      { value: 'cod', label: 'Thanh toán khi nhận hàng (COD)', icon: 'bi-truck' },
      { value: 'momo', label: 'Ví điện tử Momo', icon: 'momo-logo' },
    ],
    currentOrder: null
  }),
  actions: {
    setCurrentOrder(order) {
      this.currentOrder = order;
    },
    clearCurrentOrder() {
      this.currentOrder = null;
    },
    async processPayment(billingData) {
      const toast = useToast();
      const router = useRouter();
      const cartStore = useCartStore();
      const authStore = useAuthStore();

      this.isProcessing = true;
      
      try {
        const orderData = this.prepareOrderData(billingData);

        if (this.paymentMethod === 'cod') {
          return await this.processCOD(orderData);
        } else if (this.paymentMethod === 'momo') {
          return await this.processMomo(orderData);
        }
      } catch (error) {
        this.handlePaymentError(error, toast);
        return false;
      } finally {
        this.isProcessing = false;
      }
    },

    prepareOrderData(billingData) {
      const cartStore = useCartStore();
      
      return {
        billing: billingData,
        items: cartStore.cartItems.map(item => ({
          product_id: item.product_id,
          name: item.name,
          price: item.price,
          quantity: item.quantity,
          color: item.color,
          size: item.size,
          image: item.image
        })),
        subtotal: cartStore.subtotal,
        shipping: 10.00,
        discount: cartStore.discountAmount || 0,
        total: cartStore.total
      };
    },

    async processCOD(orderData) {
      const toast = useToast();
      const router = useRouter();
      const cartStore = useCartStore();
      const authStore = useAuthStore();

      try {
        const response = await axios.post(`${BASE_URL}/orders/cod`, orderData, {
          headers: {
            'Authorization': `Bearer ${authStore.accessToken}`
          }
        });

        this.currentOrder = response.data.order;
        
        toast.success('Đặt hàng COD thành công!');
        cartStore.clearCart();
        cartStore.removeCoupon();
        
        router.push({
          name: 'OrderConfirmation',
          params: { id: response.data.order.id },
          query: { payment_method: 'cod' }
        });

        return true;
      } catch (error) {
        console.error('COD Checkout error:', error);
        toast.error('Đặt hàng COD thất bại: ' + 
          (error.response?.data?.message || error.message));
        return false;
      }
    },

    async processMomo(orderData) {
      // Giữ nguyên như cũ
    },

    handlePaymentError(error, toast) {
      console.error('Payment error:', error);
      let errorMessage = 'Thanh toán thất bại';
      
      if (error.response) {
        if (error.response.status === 422) {
          errorMessage = 'Dữ liệu không hợp lệ. Vui lòng kiểm tra lại thông tin.';
        } else if (error.response.data?.message) {
          errorMessage += ': ' + error.response.data.message;
        }
      } else if (error.message) {
        errorMessage += ': ' + error.message;
      }
      
      toast.error(errorMessage);
    }
  }
});