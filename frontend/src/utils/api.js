axios.interceptors.response.use(
    // Xử lý response thành công
    response => response,
    
    // Xử lý lỗi (phần quan trọng)
    error => {
      // 1. Xử lý timeout
      if (error.code === 'ECONNABORTED') {
        throw { message: 'Request timeout' };
      }
      
      // 2. Xử lý mất kết nối mạng
      if (!error.response) {
        throw { message: 'Network error' };
      }
      
      // 3. Các lỗi khác (400, 401, 500...)
      return Promise.reject(error);
    }
  );