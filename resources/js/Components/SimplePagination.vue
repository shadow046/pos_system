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
    <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t bg-gray-50 sm:grid-cols-9">
        <span class="flex items-center col-span-3"></span>
        <span class="col-span-2"></span>
        <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
            <nav aria-label="Table navigation">
            <ul class="inline-flex items-center">
                <li>
                    <a :class="{'text-gray-900' : data.prev_page_url!= null}" class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-yellow" :href="data.prev_page_url">
                     Previous
                    </a>
                </li>
                <li>
                    <a class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-yellow" :href="data.next_page_url" :class="{'text-gray-900' : data.next_page_url!= null}">
                    Next
                    </a>
                </li>
            </ul>
            </nav>
        </span>
    </div>
</template>