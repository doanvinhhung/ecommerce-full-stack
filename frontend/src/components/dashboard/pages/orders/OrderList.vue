<script setup lang="ts">
import SideBar from '../../layouts/SideBar.vue';
import { useOrderListStore } from '@/stores/useOrderListStore';
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const orderStore = useOrderListStore();
const currentPage = ref(1);

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(price);
};

onMounted(async () => {
    await orderStore.fetchOrders();
});

const changePage = async (page) => {
    if (page < 1 || page > orderStore.pagination.last_page) return;
    currentPage.value = page;
    await orderStore.fetchOrders(page);
};
</script>

<template>
    <SideBar />
    
    <div class="row">
  <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
    <div class="dashboard_content">
      <h3><i class="fas fa-list-ul"></i> My Orders</h3>
      
      <div v-if="orderStore.loading" class="text-center py-4">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p>Loading orders...</p>
      </div>
      
      <div v-else-if="orderStore.error" class="alert alert-danger">
        {{ orderStore.error }}
      </div>
      
      <div v-else class="wsus__dashboard_order">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th class="package">Order Code</th>
                <th class="p_date">Purchase Date</th>
                <th class="e_date">Expired Date</th>
                <th class="price">Total</th>
                <th class="method">Payment Method</th>
                <th class="method">Transaction Id</th>
                <th class="status">Status</th>
                <th class="status">Action </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in orderStore.orders" :key="order.id">
                <td class="package">
                  <span class="">#{{ order.order_code }}</span>
                </td>
                <td class="p_date">{{ formatDate(order.created_at) }}</td>
                <td class="e_date">{{ formatDate(order.expired_at) }}</td>
                <td class="price">{{ formatPrice(order.total) }}</td>
                <td class="method">
                  <span class="badge" 
                    :class="{
                      'bg-primary': order.payment_method === 'cod',
                      'bg-success': order.payment_method === 'momo'
                    }">
                    {{ order.payment_method === 'cod' ? 'COD' : 'Momo' }}
                  </span>
                </td>
                <!-- <td class="tr_id">
  <div v-for="transaction in order.transactions" :key="transaction.id">
    {{ transaction.transaction_code }}
  </div>
</td> --><td class="method">
  <span v-if="order.transactions && order.transactions.length > 0">
    {{ order.transactions[0].transaction_code }}
  </span>
  <span v-else>
    No transaction
  </span>
</td>


                <td class="status">
                  <span class="badge" 
                    :class="{
                      'bg-warning': order.status === 'pending',
                      'bg-info': order.status === 'processing',
                      'bg-success': order.status === 'completed',
                      'bg-danger': order.status === 'cancelled'
                    }">
                    {{ order.status }}
                  </span>
                </td>
                <td class="status">
                  <router-link 
                    :to="{ name: 'order-invoice', params: { id: order.id } }" 
                    class="btn btn-sm btn-outline-primary">
                    View
                  </router-link>
                </td>
              </tr>
              
              <tr v-if="orderStore.orders.length === 0">
                <td colspan="7" class="text-center py-4">
                  You don't have any orders yet
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div id="pagination" v-if="orderStore.pagination.last_page > 1">
          <nav aria-label="Page navigation">
            <ul class="pagination">
              <li class="page-item" :class="{ disabled: currentPage === 1 }">
                <a class="page-link" href="#" aria-label="Previous" @click.prevent="changePage(currentPage - 1)">
                  <i class="fas fa-chevron-left"></i>
                </a>
              </li>

              <li v-for="page in orderStore.pagination.last_page" 
                  :key="page"
                  class="page-item" 
                  :class="{ active: currentPage === page }">
                <a class="page-link" href="#" @click.prevent="changePage(page)">
                  {{ page }}
                </a>
              </li>

              <li class="page-item" :class="{ disabled: currentPage === orderStore.pagination.last_page }">
                <a class="page-link" href="#" aria-label="Next" @click.prevent="changePage(currentPage + 1)">
                  <i class="fas fa-chevron-right"></i>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

</template>

