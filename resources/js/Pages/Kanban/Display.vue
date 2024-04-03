<script setup>
    import { ref } from 'vue';
    import {onMounted} from 'vue';
    import { Head } from '@inertiajs/vue3';
    import moment from 'moment';

    const currentTime = ref('');

    const newData = ref({
        columns: {
            preparing: {
                orders: [],
            },
            serving: {
                orders: [],
            },
        }
    })

    onMounted(() => {
        setInterval(preparingTransactions, 10000);
        setInterval(servingTransactions, 10000);

        // Update time and date every second
        setInterval(updateClock, 1000);
        updateClock();
    })

    async function preparingTransactions() {
        let response = await (await fetch(route('api.transactions.preparing'))).json();
        newData.value.columns.preparing.orders = response.data
    }

    async function servingTransactions() {
        let response = await (await fetch(route('api.transactions.serving'))).json();
        newData.value.columns.serving.orders = response.data
    }

    const updateClock = () => {
        const now = new Date();
        currentTime.value = now.toLocaleTimeString();
    };
</script>

<template>
    <header>
    <Head title="Orders" /></header>
    <div class="flex flex-col w-screen h-screen overflow-auto text-gray-700 bg-white">
        <div class="px-10 mt-6 flex justify-center">
            <span class="text-xs font-bold">
                {{moment().format("MMMM DD, YYYY")}} at
                <span>{{currentTime}}</span>
            </span>
        </div>

        <div class="h-full mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2  items-center">

            <div class="relative mb-2 ml-4 mr-4 rounded-md h-full border-gray-100 border-2 border-solid p-2 bg-gray-50 flex flex-col flex-shrink-0"  v-for="(column, index) in newData.columns" :key="index">
                <div class="flex items-center flex-shrink-0 h-10 px-2">
                    <span class="block text-lg font-bold capitalize">{{index}}</span>
                </div>

                <div class="grid grid-cols-3 gap-6">
                    <div class="justify-start bg-white flex flex-wrap rounded-lg shadow-md p-2 border-gray-50 border-solid border-2" v-for="transaction in column.orders">
                        <span class="text-xl font-extrabold">{{ transaction.receipt_no }}</span>
                    </div>
                    <div class="col-span-4"></div>
                </div>
            </div>
            </div>
    </div>
</template>