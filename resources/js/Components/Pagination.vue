<script setup>
    import { Link }  from '@inertiajs/vue3';

    defineProps({
        data: {
            type: Object,
            default: () => ({}),
        },
    });
</script>


<template>
    <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t bg-gray-50 sm:grid-cols-9" v-if="data.links && data.links.length > 3">
        <span class="flex items-center col-span-3"></span>
        <span class="col-span-2"></span>
        <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
            <nav aria-label="Table navigation">
            <ul class="inline-flex items-center">
                <li v-for="(link, k) in data.links" :key="k">
                    <a class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-yellow"
                        v-html="link.label"
                        :href="link.url"
                        :class="{'px-3 py-1 text-white transition-colors duration-150 bg-yellow-400 border border-r-0 border-yellow-300 rounded-md focus:outline-none focus:shadow-outline-yellow': link.active}"
                    >
                    </a>
                </li>
            </ul>
            </nav>
        </span>
    </div>
</template>