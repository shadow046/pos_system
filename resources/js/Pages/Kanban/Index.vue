<script setup>
    import { ref } from 'vue';
    import {onMounted} from 'vue';
    import OrderDetail from '@/Components/OrderDetail.vue';
    import axios from 'axios';
    import { VueDraggableNext } from 'vue-draggable-next';
    import { Head } from '@inertiajs/vue3';
import moment from 'moment';

    const currentTime = ref('');
    const currentDate = ref('');

    const newData = ref({
        columns: {
            pending: {
                orders: [],
            },
            preparing: {
                orders: [],
            },
            serving: {
                orders: [],
            },
            completed: {
                orders: [],
            },
            void: {
                orders: [],
            },
        }
    })

    onMounted(() => {
        pendingTransactions();
        preparingTransactions();
        servingTransactions();
        completedTransactions();
        voidTransactions();

        // Update time and date every second
        setInterval(updateClock, 1000);
        updateClock();
    })

    const changeStatus = (uuid, status) => {
        axios.put(route('api.transactions.change-status', uuid), {
            status: status,
        })
        .then(response => {
            console.log(response)
        })
        .catch(error => {
        console.error('Error:', error);
        });
    };

    const onCardMoved = (event, column) => {
    if (event.added) {
        changeStatus(event.added.element.uuid, column)
    }
    };

    async function pendingTransactions() {
        let response = await (await fetch(route('api.transactions.pending'))).json();
        newData.value.columns.pending.orders = response.data
    }

    async function preparingTransactions() {
        let response = await (await fetch(route('api.transactions.preparing'))).json();
        newData.value.columns.preparing.orders = response.data
    }

    async function servingTransactions() {
        let response = await (await fetch(route('api.transactions.serving'))).json();
        newData.value.columns.serving.orders = response.data
    }

    async function completedTransactions() {
        let response = await (await fetch(route('api.transactions.completed'))).json();
        newData.value.columns.completed.orders = response.data
    }

    async function voidTransactions() {
        let response = await (await fetch(route('api.transactions.void'))).json();
        newData.value.columns.void.orders = response.data
    }

    const updateClock = () => {
        const now = new Date();
        currentTime.value = now.toLocaleTimeString();
        currentDate.value = now.toLocaleDateString();
    };
</script>

<template>
    <header>
    <Head title="Orders" /></header>
    <div class="flex flex-col w-screen h-screen overflow-auto text-gray-700 bg-white">
        <div class="px-10 mt-6 flex justify-between">
            <h1 class="text-2xl font-bold mr-2">Orders</h1>
            <span class="text-xs font-bold">
                {{moment(currentDate).format("MMMM DD, YYYY")}} at
                <span>{{currentTime}}</span>
            </span>
            <a :href="route('dashboard')" class="font-medium text-sm font-bold text-white px-4 py-2 bg-yellow-500 rounded-md">
                <span >Back</span>
            </a>
        </div>
        <div class="flex flex-grow px-10 mt-4 space-x-6 overflow-auto kanban-board">
            <div class="h-full kanban-column flex flex-col flex-shrink-0 w-72 rounded-md border-gray-100 border-2 border-solid p-2 bg-gray-50" v-for="(column, index) in newData.columns" :key="index" >
                <div class="flex items-center flex-shrink-0 h-10 px-2">
                    <span class="block text-sm font-semibold capitalize">{{index}}</span>
                    <span class="flex items-center justify-center w-5 h-5 ml-2 text-sm font-semibold text-indigo-500 bg-white rounded bg-opacity-30" >{{column.orders.length}}</span>
                </div>
                <VueDraggableNext
                    :list="column.orders"
                    class="kanban-cards h-full"
                    :draggable="false"
                    group="kanban"
                    :sort="false"
                    @change="onCardMoved($event, index)"
                    v-if="['completed', 'void'].includes(index)"
                >
                <OrderDetail v-for="transaction in column.orders" 
                    :transaction="transaction">
                </OrderDetail>
                </VueDraggableNext>
                <VueDraggableNext
                    :list="column.orders"
                    class="kanban-cards h-full"
                    group="kanban"
                    :sort="false"
                    @change="onCardMoved($event, index)"
                    v-else
                >
                <OrderDetail v-for="transaction in column.orders" 
                    :transaction="transaction">
                </OrderDetail>
                </VueDraggableNext>
            </div> 
            <div class="flex-shrink-0 w-6"></div>
        </div>
    </div>


</template>