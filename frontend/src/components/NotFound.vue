<template>
    <div class="not-found">
      <h1>404 - Trang không tìm thấy</h1>
      <p>Xin lỗi, trang bạn yêu cầu không tồn tại.</p>
      <router-link to="/">Trở về trang chủ</router-link>
    </div>
  </template>
  
  <script>
  export default {
    name: "NotFound",
  };
  </script>
  
  <style scoped>
  .not-found {
    text-align: center;
    padding: 50px;
  }
  
  h1 {
    font-size: 36px;
    color: #ff0000;
  }
  
  p {
    font-size: 18px;
  }
  
  router-link {
    color: #007bff;
    text-decoration: none;
    font-size: 18px;
  }
  
  router-link:hover {
    text-decoration: underline;
  }
  </style>
  