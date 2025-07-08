<script setup>
import { ref, onMounted } from "vue";
import { ToastContainer } from "./components/index.js";
import { useToast } from "./composables/index.js";

const toastContainerRef = ref(null);
const { registerContainer } = useToast();

onMounted(() => {
  if (toastContainerRef.value) {
    registerContainer(toastContainerRef.value);
  }
});
</script>

<template>
  <div id="app">
    <router-view />
    <ToastContainer ref="toastContainerRef" />
  </div>
</template>

<style scoped></style>
