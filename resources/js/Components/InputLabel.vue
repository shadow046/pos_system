<script setup>
defineProps(['value', 'required']);
</script>

<template>
    <label class="block font-medium text-sm text-gray-700">
        <span v-if="value">{{ value }} <span v-if="required && required == 'true'" class="text-red-500">*</span> </span>
        <span v-else><slot /></span>
    </label>
</template>
