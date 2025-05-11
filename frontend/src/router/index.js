import Login from "@/components/auth/Login.vue";
import Register from "@/components/auth/Register.vue";
import Cart from "@/components/cart/Cart.vue";
import Home from "@/components/Home.vue";
import ProductDetail from "@/components/products/ProductDetail.vue";
import Shop from "@/components/shop/Shop.vue";

import { createRouter, createWebHashHistory } from "vue-router";
import Category from "@/components/shop/filter/Category.vue";
import { useAuthStore } from "@/stores/useAuthStore";

import MyProfile from "@/components/dashboard/pages/profile/MyProfile.vue";
import Checkout from "@/components/checkout/Checkout.vue";
import OrderConfirmation from "@/components/checkout/OrderConfirmation.vue";
import PaymentResult from "@/components/checkout/PaymentResult.vue";
import OrderList from "@/components/dashboard/pages/orders/OrderList.vue";
import OrderInvoice from "@/components/dashboard/pages/orders/OrderInvoice.vue";

const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: "/",
      name: "home",
      component: Home,
      meta: {
        requiresGuest: true, // Chỉ truy cập khi chưa đăng nhập
        layout: "default",
      },
    },

    {
      path: "/login",
      name: "login",
      component: Login,
      meta: {
        layout: "default",
      },
    },
    {
      path: "/shop",
      name: "shop",
      component: Shop,
      meta: {
        layout: "default",
      },
      props: (route) => ({
        query: {
          ...route.query,
          sort: route.query.sort || "newest", // Đảm bảo luôn có sort
        },
      }),
    },
    {
      path: "/product/:slug",
      name: "ProductDetail",
      component: ProductDetail,
      props: true, // Truyền slug như prop
      meta: {
        layout: "default",
      },
    },

    {
      path: "/cart",
      name: "Cart",
      component: Cart,
      meta: {
        layout: "default",
      },
    },
    {
      path: "/checkout",
      name: "checkout",
      component: Checkout,
      meta: {
        layout: "default",
        requiresAuth: true,
      },
    },
    {
      path: "/order-confirmation/:id",
      name: "OrderConfirmation",
      component: () => import("@/components/checkout/OrderConfirmation.vue"),
      meta: {
        requiresAuth: true, // Yêu cầu đăng nhập
        layout: "default", // Layout sử dụng
      },
      props: (route) => ({
        id: route.params.id,
        order_code: route.query.order_code, // Nhận order_code từ URL
        payment_method: route.query.payment_method, // Nhận payment_method từ URL
      }),
    },
    {
      path: "/payment/result",
      name: "PaymentResult",
      component: () => PaymentResult,
      meta: {
        layout: "default",
      },
    },
    // Thêm route mới cho category
    {
      path: "/category/:slug",
      name: "Category",
      component: Category,
      props: true, // Truyền slug như prop
      meta: {
        layout: "default",
      },
    },
    // Route catch-all cho 404
    {
      path: "/:pathMatch(.*)*",
      name: "NotFound",
      component: () => import("@/components/NotFound.vue"), // Tạo component này
      meta: {
        layout: "default",
      },
    },
    {
      path: "/dashboard",
      name: "dashboard",
      component: () => import("@/components/dashboard/layouts/Master.vue"),
      meta: { layout: "dashboard", requiresAuth: true },
      children: [
        {
          path: "/my-profile",
          name: "my-profile",
          component: MyProfile,
        },
        {
          path: '/user/orders',
          name: 'user-orders',
          component:OrderList ,
          meta: {
              requiresAuth: true,
             
          }
      },
      {
          path: '/user/orders/:id',
          name: 'order-invoice',
          component:OrderInvoice,
          meta: {
              requiresAuth: true,
             
          },
          props: true
      }
      ],
    },
  ],
});
// router.js
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  // 1. Danh sách các route public (không cần login)
  const publicRoutes = [
    "/",
    "/login",
    "/register",
    "/shop",
    "/product/*",
    "/category/*",
  ];

  // 2. Kiểm tra route hiện tại có phải public không
  const isPublicRoute = publicRoutes.some((route) => {
    if (route.endsWith("/*")) {
      return to.path.startsWith(route.replace("/*", ""));
    }
    return to.path === route;
  });

  // 3. Nếu route yêu cầu auth nhưng chưa login
  if (!isPublicRoute && !authStore.isLoggedIn) {
    return next("/login");
  }

  // 4. Nếu đã login nhưng truy cập trang auth (login/register)
  if (
    (to.path === "/login" || to.path === "/register") &&
    authStore.isLoggedIn
  ) {
    return next("/");
  }

  // 5. Cho phép truy cập
  next();
});

export default router;
