import { defineStore } from "pinia";
import { useToast } from "vue-toastification";
import api from "@/helpers/api";
import { BASE_URL } from "@/helpers/config";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    isLoggedIn: false,
    user: null,
    accessToken: null,
    validationErrors: {},
    isLoading: false,
  }),

  persist: true,

  actions: {
    initAuthInterceptor(router) {
      // Gắn token vào mỗi request
      api.interceptors.response.use(
        (response) => response,
        async (error) => {
          const originalRequest = error.config;
      
          if (error.response?.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true;
      
            try {
              const refreshed = await this.refreshToken();
              if (refreshed) {
                originalRequest.headers.Authorization = `Bearer ${this.accessToken}`;
                return api(originalRequest);
              }
            } catch (refreshError) {
              console.error("Refresh token failed:", refreshError);
            }
      
            this.clearAuthData();
      
            // Chỉ redirect nếu không phải trang product detail
            if (!window.location.pathname.includes('/product/')) {
              router.push("/login");
            }
            
            return Promise.reject(error);
          }
      
          return Promise.reject(error);
        }
      );
    },

    resetValidationErrors() {
      this.validationErrors = {};
    },

    async refreshToken() {
      if (!this.accessToken) return false;

      try {
        const response = await api.post("auth/refresh");
        this.accessToken = response.data.access_token;
        return true;
      } catch (error) {
        this.clearAuthData();
        return false;
      }
    },

    setUserData(userData) {
      this.user = userData;
      this.isLoggedIn = true;
    },

    async fetchCurrentUser() {
      if (!this.accessToken) return;

      try {
        const response = await api.get("auth/user");
        this.setUserData(response.data.user);
      } catch (error) {
        if (error.response?.status === 401) {
          this.clearAuthData();
        }
        console.error("Fetch user error:", error);
      }
    },

    async updateProfile(formData) {
      this.isLoading = true;
      this.resetValidationErrors();
      const toast = useToast();

      try {
        const response = await api.post("auth/profile", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });

        this.setUserData(response.data.user);
        toast.success("Profile updated successfully");
        return response.data;
      } catch (error) {
        if (error.response?.status === 422) {
          this.validationErrors = error.response.data.errors;
        } else {
          toast.error(error.response?.data?.message || "Failed to update profile");
        }
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async changePassword(passwordData) {
      this.isLoading = true;
      this.resetValidationErrors();
      const toast = useToast();

      try {
        await api.put("auth/password", passwordData);
        toast.success("Password changed successfully");
        return true;
      } catch (error) {
        if (error.response?.status === 422) {
          this.validationErrors = error.response.data.errors;
        }
        toast.error(error.response?.data?.message || "Failed to change password");
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    clearAuthData() {
      this.isLoggedIn = false;
      this.user = null;
      this.accessToken = null;
      this.validationErrors = {};
    },

    getError(field) {
      return this.validationErrors?.[field]?.[0] || "";
    },

    async register(userData) {
      this.resetValidationErrors();
      this.isLoading = true;
      const toast = useToast();

      try {
        const response = await api.post("auth/register", userData);
        this.isLoggedIn = true;
        this.user = response.data.user;
        this.accessToken = response.data.access_token;
        toast.success("Registration successful!");
        return { success: true };
      } catch (error) {
        if (error.response?.status === 422) {
          this.validationErrors = error.response.data.errors;
          toast.error("Please fix the validation errors");
        } else {
          toast.error(error.response?.data?.message || "Registration failed");
        }
        return { success: false };
      } finally {
        this.isLoading = false;
      }
    },

    async login(credentials) {
      this.resetValidationErrors();
      this.isLoading = true;
      const toast = useToast();

      try {
        const response = await api.post("auth/login", credentials);
        this.isLoggedIn = true;
        this.user = response.data.user;
        this.accessToken = response.data.access_token;
        toast.success("Login successful!");
        return { success: true };
      } catch (error) {
        if (error.response?.status === 401) {
          toast.error("Invalid credentials");
        } else if (error.response?.status === 422) {
          this.validationErrors = error.response.data.errors;
        } else {
          toast.error("Login failed. Please try again.");
        }
        return { success: false };
      } finally {
        this.isLoading = false;
      }
    },

    async logout() {
      if (!this.accessToken) {
        this.clearAuthData();
        return false;
      }

      try {
        await api.post("auth/logout");
      } catch (error) {
        console.error("Logout error:", error);
      } finally {
        this.clearAuthData();
      }

      return true;
    },
  },
});
