<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="/admin/assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Rocker</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{route('admin.index')}}" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
            {{-- <ul>
                <li> <a href="index.html"><i class='bx bx-radio-circle'></i>Default</a>
                </li>
                <li> <a href="index2.html"><i class='bx bx-radio-circle'></i>Alternate</a>
                </li>
                <li> <a href="index3.html"><i class='bx bx-radio-circle'></i>Graphical</a>
                </li>
            </ul> --}}
        </li>

        <li class="menu-label">Managerment</li>
        <li>
            <a href="{{ route('admin.categories.index') }}">
                <div class="parent-icon"><i class='bx bx-folder-open'></i>
                </div>
                <div class="menu-title">Categories</div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.brands.index') }}">
                <div class="parent-icon"><i class='bx bx-folder-open'></i>
                </div>
                <div class="menu-title">Brands</div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.colors.index') }}">
                <div class="parent-icon"><i class='bx bx-folder-open'></i>
                </div>
                <div class="menu-title">Color</div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.sizes.index') }}">
                <div class="parent-icon"><i class='bx bx-folder-open'></i>
                </div>
                <div class="menu-title">Size</div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.products.index') }}">
                <div class="parent-icon"><i class='bx bx-folder-open'></i>
                </div>
                <div class="menu-title">Product</div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.coupons.index') }}">
                <div class="parent-icon"><i class='bx bx-folder-open'></i>
                </div>
                <div class="menu-title">Coupons</div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.orders.index') }}">
                <div class="parent-icon"><i class='bx bx-cart'></i>
                </div>
                <div class="menu-title">Orders</div>
            </a>
        </li>


        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-book-reader'></i>
                </div>
                <div class="menu-title">Footer</div>
            </a>
            <ul>

            </ul>
        </li>
    </ul>
    <!--end navigation-->
</div>

