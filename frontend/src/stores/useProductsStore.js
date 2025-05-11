import { BASE_URL } from "@/helpers/config"; // Địa chỉ API gốc
import axios from "axios"; // Thư viện Axios để thực hiện các yêu cầu HTTP
import { defineStore } from "pinia"; // Pinia để tạo store cho trạng thái ứng dụng

// Định nghĩa một store với tên "products"
export const useProductsStore = defineStore("products", {
  // Khai báo state để lưu trữ dữ liệu
  state: () => ({
    products: [], // Danh sách sản phẩm
    product: null, // Chi tiết một sản phẩm duy nhất
    brands: [], // Danh sách các thương hiệu
    categories: [], // Danh sách các danh mục
    colors: [], // Danh sách các màu sắc
    sizes: [], // Danh sách các kích cỡ
    isLoading: false, // Trạng thái đang tải dữ liệu
    error: null, // Lỗi khi có vấn đề xảy ra
    activeFilters: {}, // Bộ lọc đang được áp dụng (ví dụ: giá, thương hiệu, màu sắc...)
    pagination: {
      current_page: 1, // Trang hiện tại
      last_page: 1, // Trang cuối cùng
      per_page: 12, // Số sản phẩm trên mỗi trang
      total: 0, // Tổng số sản phẩm
    },
    selectedCategory: null, // Danh mục đang được chọn
    selectedBrand: null, // Thương hiệu đang được chọn
    searchTerm: "", // Từ khóa tìm kiếm
  }),

  // Định nghĩa các actions (hàm xử lý logic trong store)
  actions: {
    // Lấy tất cả sản phẩm từ API
    async fetchAllProducts() {
      this.isLoading = true; // Bắt đầu tải dữ liệu
      try {
        // Gửi yêu cầu GET để lấy tất cả sản phẩm từ API
        const response = await axios.get(`${BASE_URL}/products`);
        // Cập nhật các giá trị trong state từ dữ liệu API trả về
        this.products = response.data.data;
        this.brands = response.data.brands;
        this.categories = response.data.categories;
        this.colors = response.data.colors;
        this.sizes = response.data.sizes;
        this.isLoading = false; // Kết thúc tải dữ liệu
      } catch (error) {
        console.error(error); // In lỗi nếu có
        this.isLoading = false; // Dừng trạng thái tải nếu gặp lỗi
      }
    },

    // Lấy sản phẩm theo danh mục từ API
    async fetchProductsByCategory(categoryId) {
      this.isLoading = true; // Bắt đầu tải dữ liệu
      try {
        // Gửi yêu cầu GET với tham số category_id để lọc sản phẩm theo danh mục
        const response = await axios.get(
          `${BASE_URL}/products?category_id=${categoryId}`
        );
        // Cập nhật danh sách sản phẩm với dữ liệu trả về
        this.products = response.data.data;
      } catch (error) {
        console.error("Lỗi khi lọc sản phẩm:", error); // In lỗi nếu có
      } finally {
        this.isLoading = false; // Kết thúc tải dữ liệu
      }
    },

    // Hàm chung để gọi API và lấy dữ liệu với tham số URL và params
    async fetchData(url, params) {
      try {
        // Gửi yêu cầu GET với các tham số đã truyền vào
        const { data } = await axios.get(url, { params });
        return data; // Trả về dữ liệu nếu thành công
      } catch (error) {
        // Nếu có lỗi, gán thông báo lỗi vào state error
        this.error = error.response?.data?.message || "An error occurred";
        return null; // Trả về null nếu có lỗi
      }
    },

    // Lấy sản phẩm đã lọc với các bộ lọc và phân trang
    async fetchFilteredProducts(filters = {}, page = 1) {
      this.isLoading = true; // Bắt đầu tải dữ liệu
      const params = new URLSearchParams({ ...filters, page }); // Chuyển filters và page thành URLSearchParams
      // Gọi hàm fetchData để lấy dữ liệu với các tham số đã chuẩn bị
      const response = await this.fetchData(`${BASE_URL}/products`, params);

      if (response) {
        // Nếu có dữ liệu trả về, cập nhật state với các dữ liệu mới
        this.products = response.data;
        this.brands = response.brands || [];
        this.categories = response.categories || [];
        this.colors = response.colors || [];
        this.sizes = response.sizes || [];
        this.pagination = response.meta || {}; // Cập nhật thông tin phân trang
      }
      this.isLoading = false; // Kết thúc tải dữ liệu
    },

    // Lấy chi tiết sản phẩm theo slug
    async fetchProductDetail(slug) {
      this.isLoading = true; // Bắt đầu tải dữ liệu
      this.error = null; // Reset lỗi cũ
      const response = await this.fetchData(`${BASE_URL}/product/${slug}/show`);

      if (response) {
        this.product = response; // Cập nhật chi tiết sản phẩm
      }
      this.isLoading = false; // Kết thúc tải dữ liệu
    },

    // Xóa tất cả bộ lọc và tải lại sản phẩm
    async clearAllFilters() {
      this.activeFilters = {}; // Reset bộ lọc
      this.selectedCategory = null; // Reset danh mục đã chọn
      this.selectedBrand = null; // Reset thương hiệu đã chọn
      this.searchTerm = ""; // Reset từ khóa tìm kiếm
      await this.fetchFilteredProducts(); // Gọi lại API để lấy sản phẩm đã lọc
    },

    // Thay đổi trang khi người dùng chuyển trang
    async changePage(page) {
      if (page >= 1 && page <= this.pagination.last_page) {
        // Nếu trang hợp lệ, gọi API với trang mới
        await this.fetchFilteredProducts(this.activeFilters, page);
      }
    },

    // Sắp xếp sản phẩm theo kiểu sắp xếp đã chọn
    async sortProducts(sortType) {
      await this.fetchFilteredProducts({
        ...this.activeFilters,
        sort: sortType, // Thêm tham số sắp xếp vào query
      });
    },
  },

  // Định nghĩa các getters (hàm lấy dữ liệu từ state)
  getters: {
    // Lấy tên danh mục theo ID
    getCategoryName: (state) => (id) => {
      const category = state.categories.find(c => c.id == id);
      return category?.name || id; // Trả về tên danh mục hoặc ID nếu không tìm thấy
    },

    // Lấy tên thương hiệu theo ID
    getBrandName: (state) => (id) => {
      const brand = state.brands.find(b => b.id == id);
      return brand?.name || id; // Trả về tên thương hiệu hoặc ID nếu không tìm thấy
    },

    // Lấy tên kích cỡ theo ID
    getSizeName: (state) => (id) => {
      const size = state.sizes.find(s => s.id == id);
      return size?.name || id; // Trả về tên kích cỡ hoặc ID nếu không tìm thấy
    },

    // Lấy tên màu sắc theo ID
    getColorName: (state) => (id) => {
      const color = state.colors.find(c => c.id == id);
      return color?.name || id; // Trả về tên màu sắc hoặc ID nếu không tìm thấy
    },

    // Kiểm tra xem có bộ lọc đang hoạt động không
    hasActiveFilters: (state) => Object.keys(state.activeFilters).length > 0,

    // Định dạng giá tiền theo tiền tệ Việt Nam (VND)
    formatPrice: () => (price) =>
      new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
      }).format(price).replace('₫', '').trim() + ' VND',

    // Thông tin phân trang (current, total, per page)
    paginationInfo: (state) => ({
      currentPage: state.pagination.current_page,
      totalPages: state.pagination.last_page,
      perPage: state.pagination.per_page,
      totalItems: state.pagination.total,
      from:
        (state.pagination.current_page - 1) * state.pagination.per_page + 1,
      to: Math.min(
        state.pagination.current_page * state.pagination.per_page,
        state.pagination.total
      ),
    }),

    // Lọc các tùy chọn bộ lọc (thương hiệu, danh mục, màu sắc, kích cỡ)
    filterOptions: (state) => {
      // Hàm countFilter dùng để tính toán số lượng sản phẩm tương ứng với mỗi item trong danh sách bộ lọc
      const countFilter = (items, field) => {
        return items.map((item) => ({
          ...item, // Giữ nguyên thông tin của item hiện tại trong danh sách bộ lọc
          count: state.products.filter((p) => p[field]?.some((i) => i.id === item.id)).length,
          // Tính số lượng sản phẩm mà bộ lọc này tương ứng (được lọc qua trường 'field')
          // - `state.products` là danh sách sản phẩm hiện tại.
          // - `p[field]` đại diện cho mảng các giá trị thuộc tính như `brand_id`, `category_id`, `colors`, `sizes` của sản phẩm.
          // - `.some((i) => i.id === item.id)` kiểm tra xem có giá trị nào trong mảng đó có `id` trùng với `item.id`.
          // - `.length` trả về số lượng sản phẩm thỏa mãn điều kiện.
        }));
      };
    
      return {
        brands: countFilter(state.brands, 'brand_id'), // Lọc theo 'brand_id', đếm số sản phẩm thuộc từng thương hiệu
        categories: countFilter(state.categories, 'category_id'), // Lọc theo 'category_id', đếm số sản phẩm thuộc từng danh mục
        colors: countFilter(state.colors, 'colors'), // Lọc theo 'colors', đếm số sản phẩm có màu sắc tương ứng
        sizes: countFilter(state.sizes, 'sizes'), // Lọc theo 'sizes', đếm số sản phẩm có kích cỡ tương ứng
      };
    },
    

    // Số lượng sản phẩm hiện có
    productCount: (state) => state.products.length,

    // Kiểm tra xem có sản phẩm không
    hasProducts: (state) => state.products.length > 0,
  },
});
