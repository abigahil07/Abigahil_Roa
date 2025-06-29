<template>
  <span v-if="orden === campo">
    <svg v-if="asc" class="inline w-3 h-3 ml-1 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
    <svg v-else class="inline w-3 h-3 ml-1 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
  </span>
</template>

<script setup>
defineProps({
  campo: String,
  orden: String,
  asc: Boolean
});
</script> 