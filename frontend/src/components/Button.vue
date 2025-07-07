<script setup>
defineProps({
  title: String,
});

const emit = defineEmits(["click"]);
</script>

<template>
  <button @click="emit('click')" type="button" class="button">
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
</style>
