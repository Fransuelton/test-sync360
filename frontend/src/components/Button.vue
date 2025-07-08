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
  transition: background-color 0.2s;
  border: none;
  margin: 1rem 0;
}

.button:hover {
  background-color: var(--primary-color-hover);
}

.button:disabled {
  background-color: #6c757d;
  cursor: not-allowed;
  opacity: 0.6;
}

.button:disabled:hover {
  background-color: #6c757d;
}
</style>
