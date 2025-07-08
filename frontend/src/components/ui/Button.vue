<script setup>
defineProps({
  title: String,
  disabled: {
    type: Boolean,
    default: false,
  },
  type: {
    type: String,
    default: "button",
  },
});

const emit = defineEmits(["click"]);
</script>

<template>
  <button
    @click="emit('click')"
    :type="type"
    :disabled="disabled"
    class="button"
  >
    {{ title }}
  </button>
</template>

<style scoped>
.button {
  background-color: var(--primary-color);
  border-radius: 0.8rem;
  cursor: pointer;
  padding: 1.6rem;
  color: var(--button-text-color);
  font-weight: 500;
  font-size: var(--default-font-size);
  transition: all 0.2s ease;
  border: none;
  margin: 1rem 0;
  min-height: 48px; /* Acessibilidade - área mínima de toque */
}

.button:hover {
  background-color: var(--primary-color-hover);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(102, 67, 209, 0.3);
}

.button:disabled {
  background-color: #6c757d;
  cursor: not-allowed;
  opacity: 0.6;
  transform: none;
  box-shadow: none;
}

.button:disabled:hover {
  background-color: #6c757d;
  transform: none;
  box-shadow: none;
}

/* Tablet - 768px+ */
@media (min-width: 768px) {
  .button {
    padding: 1.8rem 2.4rem;
    font-size: 1.8rem;
    border-radius: 1rem;
  }
}

/* Desktop - 1024px+ */
@media (min-width: 1024px) {
  .button {
    padding: 2rem 3rem;
    font-size: 2rem;
  }

  .button:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(102, 67, 209, 0.4);
  }
}
</style>
