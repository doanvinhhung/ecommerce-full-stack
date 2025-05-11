<template>
    <div class="container my-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-body text-center p-5">
              <template v-if="loading">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Đang xử lý kết quả thanh toán...</p>
              </template>
  
              <template v-else-if="paymentSuccess">
                <div class="text-success mb-4">
                  <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                </div>
                <h3 class="mb-3">Thanh toán thành công!</h3>
                <p>Cảm ơn bạn đã mua hàng tại cửa hàng chúng tôi</p>
                <div class="payment-details mt-4 p-3 bg-light rounded">
                  <p><strong>Mã đơn hàng:</strong> {{ orderId }}</p>
                  <p><strong>Số tiền:</strong> {{ formatPrice(amount) }}</p>
                  <p><strong>Phương thức:</strong> Ví điện tử Momo</p>
                  <p v-if="transId"><strong>Mã giao dịch:</strong> {{ transId }}</p>
                </div>
              </template>
  
              <template v-else>
                <div class="text-danger mb-4">
                  <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                </div>
                <h3 class="mb-3">Thanh toán không thành công</h3>
                <p class="text-muted">{{ errorMessage }}</p>
              </template>
  
              <div class="mt-5">
                <router-link to="/" class="btn btn-primary me-2">
                  <i class="bi bi-house"></i> Về trang chủ
                </router-link>
                <router-link 
                  v-if="paymentSuccess && orderId" 
                  :to="'/orders/' + orderId" 
                  class="btn btn-outline-primary"
                >
                  <i class="bi bi-receipt"></i> Xem đơn hàng
                </router-link>
                <router-link 
                  v-else 
                  to="/checkout" 
                  class="btn btn-outline-primary"
                >
                  <i class="bi bi-arrow-repeat"></i> Thử lại
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import axios from 'axios';
  
  const route = useRoute();
  const router = useRouter();
  
  const loading = ref(true);
  const paymentSuccess = ref(false);
  const orderId = ref(route.query.orderId || '');
  const transId = ref(route.query.transId || '');
  const amount = ref(parseFloat(route.query.amount) || 0);
  const errorMessage = ref('');
  
  const formatPrice = (price) => {
    return new Intl.NumberFormat('vi-VN', {
      style: 'currency',
      currency: 'VND'
    }).format(price);
  };
  
  onMounted(async () => {
    if (!orderId.value) {
      errorMessage.value = 'Không tìm thấy thông tin đơn hàng';
      loading.value = false;
      return;
    }
  
    try {
      // Kiểm tra kết quả thanh toán từ server
      const response = await axios.get(`/api/orders/${orderId.value}/payment-status`);
      
      if (response.data.status === 'success') {
        paymentSuccess.value = true;
        transId.value = response.data.transId || transId.value;
        amount.value = response.data.amount || amount.value;
      } else {
        errorMessage.value = response.data.message || 'Thanh toán không thành công';
      }
    } catch (error) {
      errorMessage.value = error.response?.data?.message || 
                          'Có lỗi xảy ra khi kiểm tra trạng thái thanh toán';
    } finally {
      loading.value = false;
    }
  });
  </script>
  
  <style scoped>
  .payment-details {
    max-width: 400px;
    margin: 0 auto;
    text-align: left;
  }
  </style>