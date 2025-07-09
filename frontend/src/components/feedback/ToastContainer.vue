<script setup>
import { ref, computed } from "vue";
import Toast from "./Toast.vue";

const toasts = ref([]);

const sortedToasts = computed(() => {
  return toasts.value.slice().reverse();
});

const addToast = (toast) => {
  const id =
    toast.id ||
    `toast-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
  toasts.value.push({
    ...toast,
    id,
  });
  return id;
};

const removeToast = (id) => {
  const index = toasts.value.findIndex((toast) => toast.id === id);
  if (index > -1) {
    toasts.value.splice(index, 1);
  }
};

const clearAll = () => {
  toasts.value = [];
};

defineExpose({
  addToast,
  removeToast,
  clearAll,
});
</script>

<template>
  <Teleport to="body">
    <div
      v-if="toasts.length > 0"
      class="toast-container"
      role="region"
      aria-label="Notificações"
    >
      <TransitionGroup name="toast" tag="div">
        <Toast
          v-for="toast in sortedToasts"
          :key="toast.id"
          v-bind="toast"
          @close="removeToast"
        />
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<style scoped>
.toast-container {
  position: fixed;
  top: 2rem;
  right: 2rem;
  z-index: 9999;
  pointer-events: none;
}

.toast-container > div {
  pointer-events: auto;
}

.toast-move,
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

.toast-leave-active {
  position: absolute;
  right: 0;
}

@media (max-width: 768px) {
  .toast-container {
    top: 1rem;
    right: 1rem;
    left: 1rem;
  }
}
</style>
