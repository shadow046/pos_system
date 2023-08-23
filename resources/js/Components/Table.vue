<script setup>

const props = defineProps(['title', 'description']);
</script>
<template>
    <div class="w-full overflow-hidden rounded-lg shadow">
        <div class="bg-white px-2 py-5 border-b border-gray-200 sm:px-6">
            <div class="ml-2 -mt-4 flex justify-between items-center flex-wrap sm:flex-nowrap">
                <div class="ml-2 mt-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{title}}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{description}}
                    </p>
                </div>
                <div class="mt-4 flex sm:mt-0 sm:ml-4" v-if="$slots.action">
                    <slot name="action" />
                </div>
            </div>
        </div>
        <slot />
    </div>
</template>