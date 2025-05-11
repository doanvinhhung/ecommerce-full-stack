<template>
    <div class="momo-payment">
      <button 
        @click="initMomoPayment"
        class="btn btn-primary w-100"
        :disabled="isProcessing"
      >
        <span v-if="!isProcessing">
          <img src="@/assets/images/momo-logo.png" alt="Momo" style="height: 24px; margin-right: 8px;">
          Thanh toán bằng Momo
        </span>
        <span v-else>
          <span class="spinner-border spinner-border-sm" role="status"></span>
          Đang xử lý...
        </span>
      </button>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useToast } from 'vue-toastification';
  import axios from 'axios';
  
  const props = defineProps({
    amount: {
      type: Number,
      required: true
    },
    orderInfo: {
      type: String,
      default: 'Thanh toán đơn hàng'
    }
  });
  
  const emit = defineEmits(['payment-success', 'payment-error']);
  const toast = useToast();
  const isProcessing = ref(false);
  
  const initMomoPayment = async () => {
    isProcessing.value = true;
    
    try {
      const orderId = 'ORDER_' + Date.now();
      const response = await axios.post('/api/momo/create-payment', {
        amount: props.amount,
        orderId: orderId,
        orderInfo: props.orderInfo,
        returnUrl: window.location.origin + '/checkout/success?orderId=' + orderId,
        notifyUrl: window.location.origin + '/api/momo/callback'
      });
  
      if (response.data.status === 'success') {
        window.location.href = response.data.payUrl;
      } else {
        toast.error('Không thể khởi tạo thanh toán Momo');
        emit('payment-error', response.data.message);
      }
    } catch (error) {
      console.error('Momo payment error:', error);
      toast.error('Có lỗi xảy ra khi khởi tạo thanh toán');
      emit('payment-error', error.message);
    } finally {
      isProcessing.value = false;
    }
  };
  </script>
  
  <style scoped>
  .momo-payment {
    margin: 20px 0;
  }
  </style>