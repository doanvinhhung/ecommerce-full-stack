<template>

    <!--============================
        BREADCRUMB START
    ==============================-->
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>cart View</h4>
                        <ul>
                            <li><a href="#">home</a></li>
                            <li><a href="#">peoduct</a></li>
                            <li><a href="#">cart view</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        BREADCRUMB END
    ==============================-->


    <!--============================
        CART VIEW PAGE START
    ==============================-->
    <section id="wsus__cart_view" v-if="cartStore.cartItems.length">
        <div class="container">
            <div class="row">
                <div class="col-xl-9">
                    <div class="wsus__cart_list">
                        <div class="table-responsive">
                            <table>
                                <tbody>
                                    <tr class="d-flex">
                                        <th class="wsus__pro_img">
                                            product item
                                        </th>

                                        <th class="wsus__pro_name">
                                            product details
                                        </th>



                                        <th class="wsus__pro_select">
                                            quantity
                                        </th>

                                        <th class="wsus__pro_tk">
                                            price
                                        </th>
                                        <th class="wsus__pro_tk">
                                            sub total
                                        </th>
                                        <th class="wsus__pro_icon">
                                            <a  class="common_btn" @click.prevent="cartStore.clearCart()">clear cart</a>
                                        </th>
                                    </tr>
                                    <tr class="d-flex" v-for="(product, index) in cartStore.cartItems"
                                        :key="product.ref">
                                        <td class="wsus__pro_img"><img :src="product.image" alt="product"
                                                class="img-fluid w-100">
                                        </td>

                                        <td class="wsus__pro_name">
                                            <p>{{ product.name }}</p>
                                            <span>color: {{ product.color }}</span>
                                            <span>size: {{ product.size }}</span>
                                        </td>


                                        <td class="wsus__pro_select_button">
                                            <button class="decrease" @click="cartStore.decrementQty(product)">-</button>
                                            <span class="product-quantity">{{ product.quantity }}
                                            </span>
                                            <button class="increase" @click="cartStore.incrementQty(product)">+</button>
                                        </td>


                                        <td class="wsus__pro_tk">
                                            <h6>${{ product.price }}</h6>
                                        </td>
                                        <td class="wsus__pro_tk">
                                            <h6>${{ product.price * product.quantity }}</h6>
                                        </td>
                                        <td class="wsus__pro_icon">
                                            <a @click.prevent="cartStore.removeFormCart(product)" href="#"><i
                                                    class="far fa-times"></i></a>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="wsus__cart_list_footer_button" id="sticky_sidebar">
                        <h6>total cart</h6>
                        <!-- <p>subtotal: <span>$124.00</span></p> -->
                        <!-- <p>delivery: <span>$00.00</span></p> -->
                        <p>discount: <span>$10.00</span></p>
                        <p class="total"><span>total:</span> <span>${{ total }}</span></p>

                        <!-- <form>
                            <input type="text" placeholder="Coupon Code">
                            <button type="submit" class="common_btn">apply</button>
                        </form> -->
                      
                         <router-link class="common_btn mt-4 w-100 text-center" to="/checkout">Checkout</router-link>
                        <router-link class="common_btn mt-1 w-100 text-center" to="/shop"><i
                                class="fab fa-shopify"></i> go shop</router-link>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--============================
          CART VIEW PAGE START
    ==============================-->
    <section id="wsus__cart_view" v-else>
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="wsus__cart_list cart_empty p-3 p-sm-5 text-center">
                        <p class="mb-4">your shopping cart is empty</p>
                        <a href="product_grid_view.html" class="common_btn"><i class="fal fa-store me-2"></i>view our
                            product</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
          CART VIEW PAGE END
    ==============================-->
    <section id="wsus__single_banner">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="wsus__single_banner_content">
                        <div class="wsus__single_banner_img">
                            <img src="/assets/images/single_banner_2.jpg" alt="banner" class="img-fluid w-100">
                        </div>
                        <div class="wsus__single_banner_text">
                            <h6>sell on <span>35% off</span></h6>
                            <h3>smart watch</h3>
                            <a class="shop_btn" href="#">shop now</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="wsus__single_banner_content single_banner_2">
                        <div class="wsus__single_banner_img">
                            <img src="/assets/images/single_banner_3.jpg" alt="banner" class="img-fluid w-100">
                        </div>
                        <div class="wsus__single_banner_text">
                            <h6>New Collection</h6>
                            <h3>Cosmetics</h3>
                            <a class="shop_btn" href="#">shop now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
          CART VIEW PAGE END
    ==============================-->

</template>
<script setup>
import { useCartStore } from '@/stores/useCartStore';
import { computed } from 'vue';


const cartStore = useCartStore()

const total = computed(() => cartStore.cartItems.reduce((acc, item) => acc += item.price * item.quantity, 0))
</script>

<style scoped>
.wsus__pro_select_button {
    display: flex;
    align-items: center;
    justify-content: center;
}

.wsus__pro_select_button button {
    width: 30px;
    height: 30px;
    font-size: 20px;
    background-color: #f0f0f0;
    border: 1px solid #ccc;
    cursor: pointer;
    text-align: center;
}

 .product-quantity {
    width: 40px;
    height: 30px;
    text-align: center;
    font-size: 16px;
    margin: 5px 5px;

    border: 1px solid #ccc;
    background-color: #fff;
}
</style>