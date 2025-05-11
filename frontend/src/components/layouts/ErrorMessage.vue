<template>
  <transition name="fade">
    <div v-if="message" class="error-message">
      <i class="fas fa-exclamation-circle"></i>
      {{ message }}
    </div>
  </transition>
</template>

<script setup>
defineProps({
  message: String
});
</script>

<style scoped>
.error-message {
  color: #dc3545;
  font-size: 0.875rem; /* Slightly larger text for readability */
  margin-top: 0.25rem;
  padding-left: 1.5rem; /* Adjust padding for a better layout */
  line-height: 1.4; /* Improve readability */
  display: flex;
  align-items: center;
  gap: 0.4rem; /* More spacing between icon and text */
}

.error-message i {
  font-size: 1rem; /* Slightly larger icon for better visibility */
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-5px);
}
</style>
