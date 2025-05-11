// stores/useOrderListStore.js
import { defineStore } from "pinia";
import { ref } from "vue";
import axios from "axios";
import { useAuthStore } from "./useAuthStore";
import { useRouter } from "vue-router";
import { BASE_URL } from "@/helpers/config";

export const useOrderListStore = defineStore("orderList", () => {
  const authStore = useAuthStore();
  const router = useRouter();

  // State
  const orders = ref([]);
  const currentOrder = ref(null);
  const loading = ref(false);
  const error = ref(null);
  const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0,
  });

  // Actions
  const fetchOrders = async (page = 1) => {
    loading.value = true;
    error.value = null;

    try {
      const response = await axios.get(`${BASE_URL}/user/orders`, {
        params: { page },
        headers: {
          Authorization: `Bearer ${authStore.accessToken}`,
          Accept: "application/json",
        },
      });

      if (response.data.status !== "success") {
        throw new Error(response.data.message || "Failed to fetch orders");
      }

      orders.value = response.data.orders.data;
      pagination.value = {
        current_page: response.data.orders.current_page,
        last_page: response.data.orders.last_page,
        per_page: response.data.orders.per_page,
        total: response.data.orders.total,
      };
    } catch (err) {
      handleError(err);
    } finally {
      loading.value = false;
    }
  };

  const fetchOrderDetail = async (orderId) => {
    loading.value = true;
    error.value = null;

    try {
      const response = await axios.get(`${BASE_URL}/user/orders/${orderId}`, {
        headers: {
          Authorization: `Bearer ${authStore.accessToken}`,
          Accept: "application/json",
        },
      });

      if (response.data.status !== "success") {
        throw new Error(response.data.message || "Order not found");
      }

      currentOrder.value = response.data.order;
      console.log(currentOrder.value); // <-- Thêm dòng này

      return response.data.order;
    } catch (err) {
      handleError(err);
      throw err;
    } finally {
      loading.value = false;
    }
  };
  // ... Các phần code hiện có

  const cancelOrder = async (orderId) => {
    loading.value = true;
    error.value = null;

    try {
      const response = await axios.post(
        `${BASE_URL}/user/orders/${orderId}/cancel`,
        {},
        {
          headers: {
            Authorization: `Bearer ${authStore.accessToken}`,
            Accept: "application/json",
          },
        }
      );

      if (response.data.status !== "success") {
        throw new Error(response.data.message || "Failed to cancel order");
      }

      // Cập nhật lại danh sách đơn hàng
      await fetchOrders(pagination.value.current_page);

      return response.data;
    } catch (err) {
      handleError(err);
      throw err;
    } finally {
      loading.value = false;
    }
  };

 
  const handleError = (err) => {
    if (err.response?.status === 401) {
      authStore.clearAuthData();
      // KHÔNG tự động redirect ở đây nữa
      // Việc redirect sẽ do authStore interceptor xử lý
    }
    error.value = err.response?.data?.message || err.message;
    // console.error("API Error:", err);
  };
// Thêm action mới để kiểm tra đơn hàng của sản phẩm cụ thể
const hasPurchasedProduct = async (productId) => {
  // Nếu chưa có orders thì fetch trước
  if (orders.value.length === 0 && authStore.isLoggedIn) {
    try {
      await fetchOrders(1);
    } catch (error) {
      console.error("Failed to fetch orders", error);
      return false;
    }
  }
  
  return orders.value.some(order => 
    order.products?.some(item => item.id === productId)
  );
}

  return {
    hasPurchasedProduct,
    // State
    orders,
    currentOrder,
    loading,
    error,
    pagination,
    cancelOrder, // Thêm vào return
    // Actions
    fetchOrders,
    fetchOrderDetail,
  };
});
