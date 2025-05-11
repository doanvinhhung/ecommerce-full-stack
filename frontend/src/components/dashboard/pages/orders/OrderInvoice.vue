<script setup lang="ts">
import SideBar from '../../layouts/SideBar.vue';
import { useRoute, useRouter } from 'vue-router';
import { ref, onMounted, computed } from 'vue';
import { useOrderListStore } from '@/stores/useOrderListStore';
import { useToast } from 'vue-toastification';
const props = defineProps({
    id: {
        type: String,
        required: true
    }
});
const route = useRoute();
const router = useRouter();
const toast = useToast();
const orderStore = useOrderListStore();

const loading = computed(() => orderStore.loading);
const error = computed(() => orderStore.error);
const order = computed(() => orderStore.currentOrder);

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
};


onMounted(async () => {
    try {
        await orderStore.fetchOrderDetail(route.params.id);

        if (!order.value) {
            toast.error('Order not found');
            router.push({ name: 'user-orders' });
        }
    } catch (err) {
        toast.error(error.value || 'Failed to load order');
    }
});

const printInvoice = () => {
    window.print();
};
const cancelLoading = ref(false);

const canCancelOrder = computed(() => {
    return order.value?.status === 'pending';
});

const handleCancelOrder = async () => {
    if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) return;

    cancelLoading.value = true;
    try {
        await orderStore.cancelOrder(route.params.id);
        toast.success('Đơn hàng đã được hủy thành công!');
        
        // Làm mới dữ liệu đơn hàng
        await orderStore.fetchOrderDetail(route.params.id);
    } catch (err) {
        toast.error(err.message || 'Hủy đơn hàng thất bại!');
    } finally {
        cancelLoading.value = false;
    }
};
</script>

<template>
    <SideBar />

    <div class="row">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
            <div class="dashboard_content">
                <div v-if="loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading order details...</p>
                </div>

                <div v-else-if="error" class="alert alert-danger">
                    {{ error }}
                </div>

                <div v-else-if="order" class="wsus__invoice_area">
                    <div class="wsus__invoice_header">
                        <div class="wsus__invoice_content">
                            <div class="row">
                                <div class="col-xl-4 col-md-4 mb-5 mb-md-0">
                                    <div class="wsus__invoice_single">
                                        <h5>Invoice To</h5>
                                        <h6>{{ order.billing_info.name }}</h6>
                                        <p>{{ order.billing_info.email }}</p>
                                        <p>{{ order.billing_info.phone }}</p>
                                        <p>{{ order.billing_info.address }}</p>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-4 mb-5 mb-md-0">
                                    <div class="wsus__invoice_single text-md-center">
                                        <h5>Order Information</h5>
                                        <p>Order #: {{ order.order_code }}</p>
                                        <p>Date: {{ formatDate(order.created_at) }}</p>
                                        <p>Payment:
                                            <span class="badge" :class="{
                                                'bg-primary': order.payment_method === 'cod',
                                                'bg-success': order.payment_method === 'momo'
                                            }">
                                                {{ order.payment_method === 'cod' ? 'COD' : 'Momo' }}
                                            </span>
                                        </p>
                                        <p>Status:
                                            <span class="badge" :class="{
                                                'bg-warning': order.status === 'pending',
                                                'bg-info': order.status === 'processing',
                                                'bg-success': order.status === 'completed',
                                                'bg-danger': order.status === 'cancelled'
                                            }">
                                                {{ order.status }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-4">
                                    <div class="wsus__invoice_single text-md-end">
                                        <h5>Shipping Information</h5>
                                        <h6>{{ order.billing_info.name }}</h6>
                                        <p>{{ order.billing_info.address }}</p>
                                        <p>{{ order.billing_info.city }}</p>
                                        <p>{{ order.billing_info.zip }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wsus__invoice_description">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="images">Image</th>
                                            <th class="name">Product</th>

                                            <th class="amount">Price</th>
                                            <th class="quantity">Qty</th>

                                            <th class="total">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="product in order.products" :key="product.id">
                                            <td class="images">
                                                <img :src="product.pivot.image_url  || '/assets/images/default-product.jpg'"
                                                    :alt="product.name" class="img-fluid" style="max-width: 80px;">
                                            </td>
                                            <td class="name">
                                                <p>{{ product.name }}</p>
                                                <span v-if="product.pivot.color">Color: {{ product.pivot.color }}</span>
                                                <span v-if="product.pivot && product.pivot.size"> | Size: {{ product.pivot.size }}</span>
                                            </td>
                                            <td class="amount">{{ formatPrice(product.price) }}</td>
                                            <td class="quantity">{{ product.pivot.quantity }}</td>
                                            <td class="total">{{ formatPrice(product.pivot.price * product.pivot.quantity) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="wsus__invoice_footer">
                        <p><span>Subtotal:</span> {{ formatPrice(order.subtotal) }}</p>
                        <p><span>Shipping fee:</span> {{ formatPrice(order.shipping_fee) }}</p>
                        <p><span>Discount:</span> -{{ formatPrice(order.discount) }}</p>
                        <p><span>Total:</span> {{ formatPrice(order.total) }}</p>
                    </div>

                    <div class="mt-4 text-end">
                        <button class="btn btn-primary" @click="printInvoice">
                            <i class="fas fa-print me-2"></i> Print Invoice
                        </button>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
            <button 
                v-if="canCancelOrder"
                class="btn btn-danger"
                @click="handleCancelOrder"
                :disabled="cancelLoading"
            >
                <span v-if="cancelLoading" class="spinner-border spinner-border-sm me-1"></span>
                {{ cancelLoading ? 'Đang xử lý...' : 'Hủy đơn hàng' }}
            </button>

            <button class="btn btn-primary" @click="printInvoice">
                <i class="fas fa-print me-2"></i> In hóa đơn
            </button>
        </div>
                </div>

                <div v-else class="alert alert-warning">
                    Order not found
                </div>
            </div>
        </div>
    </div>
</template>

