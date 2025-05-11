import axios from "axios";
import { BASE_URL } from "./config";
import { useAuthStore } from "@/stores/useAuthStore";

const api = axios.create({
  baseURL: `${BASE_URL}/`,
});

// Gắn token tự động
api.interceptors.request.use((config) => {
  const authStore = useAuthStore();
  if (authStore.accessToken) {
    config.headers.Authorization = `Bearer ${authStore.accessToken}`;
  }
  return config;
});

export default api;
