<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-slate-100 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-slate-200 active:bg-slate-200 focus:outline-none focus:border-slate-200 focus:shadow-outline-gray transition ease-in-out duration-150">
        <slot />
    </button>
</template>