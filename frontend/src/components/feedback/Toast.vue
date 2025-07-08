<script setup>
import { computed, onMounted } from 'vue'

const props = defineProps({
  id: {
    type: String,
    required: true
  },
  type: {
    type: String,
    default: 'info',
    validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
  },
  title: {
    type: String,
    required: true
  },
  message: {
    type: String,
    default: ''
  },
  duration: {
    type: Number,
    default: 5000
  },
  persistent: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close'])

const toastIcon = computed(() => {
  const icons = {
    success: '✅',
    error: '❌',
    warning: '⚠️',
    info: 'ℹ️'
  }
  return icons[props.type]
})

const toastClass = computed(() => {
  return `toast toast--${props.type}`
})

const closeToast = () => {
  emit('close', props.id)
}

onMounted(() => {
  if (!props.persistent && props.duration > 0) {
    setTimeout(() => {
      closeToast()
    }, props.duration)
  }
})
</script>

<template>
  <div :class="toastClass">
    <div class="toast__icon">
      {{ toastIcon }}
    </div>
    
    <div class="toast__content">
      <h4 class="toast__title">{{ title }}</h4>
      <p v-if="message" class="toast__message">{{ message }}</p>
    </div>
    
    <button 
      class="toast__close" 
      @click="closeToast"
      aria-label="Fechar notificação"
    >
      ✕
    </button>
  </div>
</template>

<style scoped>
.toast {
  display: flex;
  align-items: flex-start;
  gap: 1.2rem;
  padding: 1.6rem;
  margin-bottom: 1rem;
  border-radius: 0.8rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  border-left: 4px solid;
  background-color: var(--profile-card-background-color);
  animation: slideIn 0.3s ease-out;
  position: relative;
  min-width: 320px;
  max-width: 500px;
}

.toast__icon {
  font-size: 2rem;
  flex-shrink: 0;
  margin-top: 0.2rem;
}

.toast__content {
  flex: 1;
  min-width: 0;
}

.toast__title {
  margin: 0;
  font-size: 1.4rem;
  font-weight: 600;
  line-height: 1.2;
  margin-bottom: 0.4rem;
}

.toast__message {
  margin: 0;
  font-size: 1.3rem;
  line-height: 1.4;
  color: var(--aux-text-color);
}

.toast__close {
  background: none;
  border: none;
  font-size: 1.4rem;
  cursor: pointer;
  color: var(--aux-text-color);
  padding: 0.4rem;
  border-radius: 0.4rem;
  transition: all 0.2s ease;
  flex-shrink: 0;
  width: 2.4rem;
  height: 2.4rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.toast__close:hover {
  background-color: rgba(0, 0, 0, 0.1);
  color: var(--default-text-color);
}

.toast--success {
  border-left-color: #28a745;
}

.toast--success .toast__title {
  color: #28a745;
}

.toast--error {
  border-left-color: #dc3545;
}

.toast--error .toast__title {
  color: #dc3545;
}

.toast--warning {
  border-left-color: #ffc107;
}

.toast--warning .toast__title {
  color: #e68900;
}

.toast--info {
  border-left-color: var(--primary-color);
}

.toast--info .toast__title {
  color: var(--primary-color);
}

@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.toast.toast--leaving {
  animation: slideOut 0.3s ease-in forwards;
}

@keyframes slideOut {
  from {
    transform: translateX(0);
    opacity: 1;
  }
  to {
    transform: translateX(100%);
    opacity: 0;
  }
}

@media (max-width: 768px) {
  .toast {
    min-width: 280px;
    max-width: calc(100vw - 2rem);
    margin-left: 1rem;
    margin-right: 1rem;
  }
  
  .toast__title {
    font-size: 1.3rem;
  }
  
  .toast__message {
    font-size: 1.2rem;
  }
}
</style>
