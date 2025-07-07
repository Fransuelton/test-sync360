<script setup>
import { ref } from 'vue'

defineProps({
  msg: String,
})

const count = ref(0)
</script>

<template>
  <section>
    <h1>{{ msg }}</h1>
    <button @click="count++">You clicked me {{ count }} times.</button>
    
  </section>
</template>

<style scoped>

</style>
