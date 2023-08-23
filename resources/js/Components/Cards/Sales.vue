<script setup>
    defineProps(['title', 'amount', 'icon']);
</script>
<template>
<div class="flex items-center p-4 bg-white rounded-lg shadow">
      <div v-html="icon"></div>
    <div>
      <p
        class="mb-2 text-sm font-medium text-gray-600"
      >
        {{title}}
      </p>
      <p
        class="text-lg font-semibold text-gray-700"
      >
        {{amount}}
      </p>
    </div>
</div>
</template>