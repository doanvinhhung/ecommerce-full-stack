<script setup lang="ts">
import { computed, onMounted } from 'vue';
import SearchForm from '../shop/filter/SearchForm.vue';
import { useCartStore } from '@/stores/useCartStore';
import { useAuthStore } from '@/stores/useAuthStore';
import { useRouter } from 'vue-router';
import logo from '@/assets/images/logohungmain.png';
const cartStore = useCartStore();

const total = computed(() => cartStore.cartItems.reduce((acc, item) => acc + item.price * item.quantity, 0));
const authStore = useAuthStore()
const router = useRouter();
// Đăng xuất
const handleLogout = async () => {
    const success = await authStore.logout();
    if (success) {
        router.push('/login');
    }
};

// Khi component mounted, kiểm tra user nếu đã có token
onMounted(() => {
    if (authStore.accessToken) {
        authStore.fetchCurrentUser()
    }
})

onMounted(() => {
    if (typeof $ !== 'undefined') {
        // MINI CART
        $('.wsus__cart_icon').on('click', function (event) {
            event.preventDefault();
            $('.wsus__mini_cart').addClass('show_cart');
        });

        $('.wsus_close_mini_cart').on('click', function () {
            $('.wsus__mini_cart').removeClass('show_cart');
        });

        // ✅ MOBILE MENU — đoạn bạn cần sửa
        $('.wsus__mobile_menu_icon').on('click', function () {
            $('#wsus__mobile_menu').addClass('show_m_menu');
        });

        $('.wsus__mobile_menu_close').on('click', function () {
            $('#wsus__mobile_menu').removeClass('show_m_menu');
        });
    } else {
        console.warn('jQuery not loaded.');
    }
});
</script>

<template>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-2 col-md-1 d-lg-none">
                    <div class="wsus__mobile_menu_area">
                        <span class="wsus__mobile_menu_icon"><i class="fal fa-bars"></i></span>
                    </div>
                </div>
                <div class="col-xl-2 col-7 col-md-8 col-lg-2">
                    <div class="wsus_logo_area">
                        <router-link class="wsus__header_logo" to="/">
                            <img :src="logo" alt="logo" class="img-fluid w-100">
                           <!-- <span class="text-light fw-semibold fs-6" style="margin-top: 2px; line-height: 1;margin-left: 25px ;">
                                Ecommerce
                            </span> -->
                        </router-link>
                    </div>
                </div>
                <SearchForm></SearchForm>
                <div class="col-xl-5 col-3 col-md-3 col-lg-6">
                    <div class="wsus__call_icon_area">
                        <div class="wsus__call_area">
                            <div class="wsus__call">
                                <i class="fas fa-user-headset"></i>
                            </div>
                            <div class="wsus__call_text">
                                <p>example@gmail.com</p>
                                <p>+569875544220</p>
                            </div>
                        </div>
                        <ul class="wsus__icon_area">
                            <li><a href="wishlist.html"><i class="fal fa-heart"></i><span>05</span></a></li>
                            <li><a href="compare.html"><i class="fal fa-random"></i><span>03</span></a></li>
                            <li><a class="wsus__cart_icon" href="#"><i class="fal fa-shopping-bag"></i><span>{{
                                        cartStore.cartItems.length }}</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="wsus__mini_cart">
            <h4>shopping cart <span class="wsus_close_mini_cart"><i class="far fa-times"></i></span></h4>
            <ul>
                <li v-for="(product, index) in cartStore.cartItems" :key="product.ref">
                    <div class="wsus__cart_img">
                        <a href="#"><img :src="product.image" alt="product" class="img-fluid w-100"></a>
                        <a class="wsis__del_icon" href="#"><i class="fas fa-minus-circle"></i></a>
                    </div>
                    <div class="wsus__cart_text">
                        <a class="wsus__cart_title" href="#">{{ product.name }}</a>
                        <p>${{ product.price }} <del>$150</del></p>
                    </div>
                </li>

            </ul>
            <h5>sub total <span>${{ total }}</span></h5>
            <div class="wsus__minicart_btn_area">
                <router-link class="common_btn" to="/cart">view cart</router-link>
                <router-link class="common_btn" to="/checkout">Checkout</router-link>

            </div>
        </div>

    </header>
    <!--============================
        MOBILE MENU START
    ==============================-->
    <section id="wsus__mobile_menu">
        <span class="wsus__mobile_menu_close"><i class="fal fa-times"></i></span>
        <ul class="wsus__mobile_menu_header_icon d-inline-flex">

            <li><a href="wishlist.html"><i class="far fa-heart"></i> <span>2</span></a></li>

            <li><a href="compare.html"><i class="far fa-random"></i> <span>3</span></a></li>
        </ul>
        <form>
            <input type="text" placeholder="Search">
            <button type="submit"><i class="far fa-search"></i></button>
        </form>

        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                    role="tab" aria-controls="pills-home" aria-selected="true">Categories</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                    role="tab" aria-controls="pills-profile" aria-selected="false">main menu</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="wsus__mobile_menu_main_menu">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <ul class="wsus_mobile_menu_category">
                            <li><a href="#"><i class="fas fa-star"></i> hot promotions</a></li>
                            <li><a href="#" class="accordion-button collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseThreew" aria-expanded="false"
                                    aria-controls="flush-collapseThreew"><i class="fal fa-tshirt"></i> fashion</a>
                                <div id="flush-collapseThreew" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <ul>
                                            <li><a href="#">men's</a></li>
                                            <li><a href="#">wemen's</a></li>
                                            <li><a href="#">kid's</a></li>
                                            <li><a href="#">others</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li><a href="#" class="accordion-button collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseThreer" aria-expanded="false"
                                    aria-controls="flush-collapseThreer"><i class="fas fa-tv"></i> electronics</a>
                                <div id="flush-collapseThreer" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <ul>
                                            <li><a href="#">Consumer Electronic</a></li>
                                            <li><a href="#">Accessories & Parts</a></li>
                                            <li><a href="#">other brands</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li><a href="#" class="accordion-button collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseThreerrp" aria-expanded="false"
                                    aria-controls="flush-collapseThreerrp"><i class="fas fa-chair-office"></i>
                                    furnicture</a>
                                <div id="flush-collapseThreerrp" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <ul>
                                            <li><a href="#">home</a></li>
                                            <li><a href="#">office</a></li>
                                            <li><a href="#">restaurent</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li><a href="#" class="accordion-button collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseThreerrw" aria-expanded="false"
                                    aria-controls="flush-collapseThreerrw"><i class="fal fa-mobile"></i> Smart
                                    Phones</a>
                                <div id="flush-collapseThreerrw" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <ul>
                                            <li><a href="#">apple</a></li>
                                            <li><a href="#">xiaomi</a></li>
                                            <li><a href="#">oppo</a></li>
                                            <li><a href="#">samsung</a></li>
                                            <li><a href="#">vivo</a></li>
                                            <li><a href="#">others</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li><a href="#"><i class="fas fa-home-lg-alt"></i> Home & Garden</a></li>
                            <li><a href="#"><i class="far fa-camera"></i> Accessories</a></li>
                            <li><a href="#"><i class="fas fa-heartbeat"></i> healthy & Beauty</a></li>
                            <li><a href="#"><i class="fal fa-gift-card"></i> Gift Ideas</a></li>
                            <li><a href="#"><i class="fal fa-gamepad-alt"></i> Toy & Games</a></li>
                            <li><a href="#"><i class="fal fa-gem"></i> View All Categories</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div class="wsus__mobile_menu_main_menu">
                    <div class="accordion accordion-flush" id="accordionFlushExample2">
                        <ul>
                            <li> <router-link to="/">home</router-link></li>
                            <li> <router-link to="/shop">shop</router-link>
                                <div id="flush-collapseThree" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample2">
                                    <div class="accordion-body">
                                        <ul>
                                            <li><a href="#">men's</a></li>
                                            <li><a href="#">wemen's</a></li>
                                            <li><a href="#">kid's</a></li>
                                            <li><a href="#">others</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <router-link to="/dashboard" class="d-flex align-items-center">
                                    <i class="far fa-user me-2"></i>
                                    My Account
                                </router-link>
                            </li>

                            <li> <router-link to="/user/orders" class="d-flex align-items-center">
                                    <i class="far fa-shopping-bag me-2"></i> My Orders
                                </router-link></li>
                            <li>
                                <button @click="handleLogout" class="dropdown-item ">
                                    <i class="far fa-sign-out-alt"></i>
                                    <h6>Logout</h6>
                                </button>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        MOBILE MENU END
    ==============================-->
</template>