<script setup>
import { ref, computed, onMounted } from 'vue';
import { useCartStore } from '@/stores/useCartStore';
import { useAuthStore } from '@/stores/useAuthStore';
import { useToast } from 'vue-toastification';
import axios from 'axios';
import { BASE_URL } from '@/helpers/config';
import { useRouter } from 'vue-router';
import { usePaymentStore } from '@/stores/usePaymentStore';

// Stores
const cartStore = useCartStore();
const authStore = useAuthStore();
const paymentStore = usePaymentStore();
const toast = useToast();
const router = useRouter();

// Refs
const couponCode = ref('');
const couponError = ref('');
const isApplyingCoupon = ref(false);
const isProcessing = ref(false);
const agreeTerms = ref(false);
// Thêm ref cho userCouponUsage
const userCouponUsage = ref(0);

const billing = ref({
    name: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    zip: '',
    notes: ''
});

// Computed properties
const subtotal = computed(() => cartStore.cartItems.reduce(
    (sum, item) => sum + (item.price * item.quantity), 0
));

const shippingFee = computed(() => 10.00);

const discount = computed(() => cartStore.discountAmount > 0 
    ? cartStore.discountAmount 
    : 0
);

const total = computed(() => subtotal.value + shippingFee.value - discount.value);

// Lifecycle hooks
onMounted(() => {
    authStore.resetValidationErrors();
    if (authStore.isLoggedIn && authStore.user) {
        billing.value = {
            name: authStore.user.name || '',
            email: authStore.user.email || '',
            phone: authStore.user.phone || '',
            address: authStore.user.address || '',
            city: authStore.user.city || '',
            zip: authStore.user.zip || '',
            notes: ''
        };
    }
});

// Methods
// Cập nhật handleApplyCoupon
const handleApplyCoupon = async () => {
  couponError.value = '';
  
  if (!couponCode.value?.trim()) {
    couponError.value = 'Vui lòng nhập mã giảm giá';
    return;
  }

  isApplyingCoupon.value = true;
  const success = await cartStore.applyCoupon(couponCode.value.trim());
  
  if (success) {
    // Update the local usage count after successful application
    userCouponUsage.value = cartStore.userCouponUsage;
  }
  
  isApplyingCoupon.value = false;
};

const validateBillingData = () => {
    const requiredFields = ['name', 'email', 'phone', 'address', 'city', 'zip'];
    const missingFields = requiredFields.filter(field => !billing.value[field]);
    
    if (missingFields.length > 0) {
        toast.error(`Vui lòng điền đầy đủ thông tin: ${missingFields.join(', ')}`);
        return false;
    }
    
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(billing.value.email)) {
        toast.error('Email không hợp lệ');
        return false;
    }
    
    if (!/^[0-9]{10,15}$/.test(billing.value.phone)) {
        toast.error('Số điện thoại không hợp lệ');
        return false;
    }
    
    if (!agreeTerms.value) {
        toast.error('Vui lòng đồng ý với điều khoản và điều kiện');
        return false;
    }
    
    return true;
};

const prepareOrderData = () => {
    const data = {
        billing: billing.value,
        items: cartStore.cartItems.map(item => ({
            product_id: item.id || item.product_id,
            name: item.name,
            price: item.price,
            quantity: item.quantity,
            color: item.color || null,
            size: item.size || null,
            image: item.image ? item.image.replace(/^https?:\/\/[^/]+/, '') : null
        })),
        subtotal: subtotal.value,
        shipping: shippingFee.value,
        discount: discount.value,
        total: total.value
    };
    // console.log('Prepared order data:', data); // <-- Thêm dòng này
    return data;
};
// Trong component checkout

const handleCODCheckout = async () => {
  if (!validateBillingData()) return;
  
  isProcessing.value = true;
  
  try {
    const orderData = {
      ...prepareOrderData(),
      coupon_code: cartStore.coupon?.code || null // Gửi coupon code lên server
    };

    const response = await axios.post(`${BASE_URL}/orders/cod`, orderData, {
      headers: {
        'Authorization': `Bearer ${authStore.accessToken}`
      }
    });

    // Xử lý thành công
    paymentStore.setCurrentOrder(response.data.order);
    cartStore.clearCart();
    cartStore.removeCoupon();
    
    toast.success('Đặt hàng thành công!');
    router.push({ name: 'OrderConfirmation', params: { id: response.data.order.id } });
    
  } catch (error) {
    handleCheckoutError(error);
  } finally {
    isProcessing.value = false;
  }
};

const handleCheckoutError = (error) => {
    console.error('Checkout error:', error);
    
    if (error.response?.status === 422) {
        const errors = error.response.data.errors;
        let errorMessage = 'Vui lòng kiểm tra lại thông tin:';
        
        for (const [field, messages] of Object.entries(errors)) {
            errorMessage += `\n- ${field}: ${messages.join(', ')}`;
        }
        
        toast.error(errorMessage);
    } else if (error.response?.status === 401) {
        toast.error('Phiên đăng nhập hết hạn. Vui lòng đăng nhập lại');
        authStore.logout();
        router.push('/login');
    } else {
        toast.error('Đặt hàng thất bại: ' + 
            (error.response?.data?.message || error.message));
    }
};
</script>

<template>
    <section id="wsus__cart_view">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="wsus__check_form">
                        <h5>Billing Details</h5>
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="wsus__check_single_form"
                                    :class="{ 'has-error': authStore.getError('name') }">
                                    <input type="text" placeholder="Name" v-model="billing.name" required>
                                    <span class="invalid-feedback" v-if="authStore.getError('name')">
                                        {{ authStore.getError('name') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-md-6">
                                <div class="wsus__check_single_form"
                                    :class="{ 'has-error': authStore.getError('email') }">
                                    <input type="email" placeholder="Email" v-model="billing.email" required>
                                    <span class="invalid-feedback" v-if="authStore.getError('email')">
                                        {{ authStore.getError('email') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-md-6">
                                <div class="wsus__check_single_form"
                                    :class="{ 'has-error': authStore.getError('phone') }">
                                    <input type="tel" placeholder="Phone" v-model="billing.phone" required>
                                    <span class="invalid-feedback" v-if="authStore.getError('phone')">
                                        {{ authStore.getError('phone') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-xl-12">
                                <div class="wsus__check_single_form"
                                    :class="{ 'has-error': authStore.getError('address') }">
                                    <input type="text" placeholder="Address" v-model="billing.address" required>
                                    <span class="invalid-feedback" v-if="authStore.getError('address')">
                                        {{ authStore.getError('address') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-md-6">
                                <div class="wsus__check_single_form"
                                    :class="{ 'has-error': authStore.getError('city') }">
                                    <input type="text" placeholder="City" v-model="billing.city" required>
                                    <span class="invalid-feedback" v-if="authStore.getError('city')">
                                        {{ authStore.getError('city') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-md-6">
                                <div class="wsus__check_single_form"
                                    :class="{ 'has-error': authStore.getError('zip') }">
                                    <input type="text" placeholder="Zip Code" v-model="billing.zip" required>
                                    <span class="invalid-feedback" v-if="authStore.getError('zip')">
                                        {{ authStore.getError('zip') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-xl-12">
                                <div class="wsus__check_single_form">
                                    <textarea cols="3" rows="4" placeholder="Order Notes"
                                        v-model="billing.notes"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-4 col-lg-5">
                    <div class="wsus__order_details" id="sticky_sidebar">
                        <h5 class="mb-4">Your Order</h5>
                        
                        <div class="col-xl-12 mb-3">
                            <div class="wsus__check_single_form">
                                <input type="text" placeholder="Nhập mã giảm giá" v-model="couponCode"
                                    class="form-control" />
                                <button class="btn btn-primary mt-2" @click.prevent="handleApplyCoupon"
                                    :disabled="isApplyingCoupon || !couponCode">
                                    {{ isApplyingCoupon ? 'Đang xử lý...' : 'Áp dụng' }}
                                </button>
                                <span v-if="couponError" class="text-danger d-block mt-2">
                                    {{ couponError }}
                                </span>

                                <div v-if="cartStore.coupon" class="mt-2 text-success">
  <p>Đã áp dụng mã: <strong>{{ cartStore.coupon.name }}</strong></p>
  <p>Số lần đã dùng: {{ cartStore.userCouponUsage }}/{{ cartStore.coupon.max_use_per_user }}</p>
  <button @click="cartStore.removeCoupon()" class="btn btn-sm btn-danger">
    Xóa mã
  </button>
</div>
                            </div>
                        </div>

                        <div class="order_items">
                            <div class="order_item mb-3" v-for="item in cartStore.cartItems" :key="item.ref">
                                <div class="d-flex align-items-start">
                                    <div class="order_item_img me-3">
                                        <img :src="item.image" :alt="item.name" class="img-fluid rounded">
                                    </div>

                                    <div class="order_item_info flex-grow-1">
                                        <h6 class="mb-1">{{ item.name }}</h6>

                                        <div class="d-flex flex-wrap text-muted small mb-1">
                                            <span v-if="item.color" class="me-2 mr-4">
                                                Màu sắc: {{ item.color }}
                                            </span>
                                            <span v-if="item.size">
                                                Size: {{ item.size }}
                                            </span>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="quantity small">
                                                Qty: {{ item.quantity }}
                                            </div>
                                            <div class="price fw-bold">
                                                ${{ (item.price * item.quantity).toFixed(2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="order_summary mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>{{ cartStore.formatPrice(subtotal) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>{{ cartStore.formatPrice(shippingFee) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2" v-if="discount > 0">
                                <span>Discount:</span>
                                <span>-{{ cartStore.formatPrice(discount) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-5 mt-3 pt-2 border-top">
                                <span>Total:</span>
                                <span>{{ cartStore.formatPrice(total) }}</span>
                            </div>
                        </div>

                        <div class="payment-methods mt-4">
                            <h5 class="mb-3">Phương thức thanh toán</h5>

                            <div class="d-grid gap-3">
                                <button @click="handleCODCheckout" class="btn btn-primary" :disabled="isProcessing">
                                    <i class="bi bi-truck me-2"></i>
                                    Thanh toán khi nhận hàng (COD)
                                    <span v-if="isProcessing" class="spinner-border spinner-border-sm ms-2"></span>
                                </button>
                            </div>
                        </div>

                        <div class="terms_area">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="agreeTerms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#">terms and conditions</a>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.order_items {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 8px;
}

.order_item {
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.order_item:last-child {
    border-bottom: none;
}

.order_item_img {
    width: 70px;
    height: 70px;
    min-width: 70px;
    overflow: hidden;
    border-radius: 8px;
    border: 1px solid #eee;
}

.order_item_img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.order_item_info h6 {
    font-size: 15px;
    line-height: 1.3;
    margin-bottom: 8px;
}

.quantity {
    color: #666;
    font-size: 13px;
}

.price {
    color: var(--theme-color);
}

.order_summary {
    font-size: 15px;
}

.order_items::-webkit-scrollbar {
    width: 5px;
}

.order_items::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.order_items::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

.order_items::-webkit-scrollbar-thumb:hover {
    background: #aaa;
}

.wsus__check_single_form.has-error {
    border-color: #dc3545;
}

.wsus__check_single_form.has-error input {
    color: #dc3545;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 0.875em;
    margin-top: 0.25rem;
    display: block;
}

#sticky_sidebar {
    position: sticky;
    top: 20px;
}
</style>