import { useAuthStore } from '@/stores/useAuthStore';

export const authPlugin = ({ app, router }) => {
  return {
    install() {
      const authStore = useAuthStore();
      authStore.initAuthInterceptor(router);
      
      if (authStore.accessToken) {
        authStore.fetchCurrentUser().catch(() => {
          authStore.clearAuthData();
        });
      }
    }
  };
};