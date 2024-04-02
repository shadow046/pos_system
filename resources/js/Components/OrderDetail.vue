<script setup>
    import { ref } from 'vue';
    import InfoButton from '@/Components/InfoButton.vue';
    import moment from 'moment';

    const props = defineProps(['transaction']);
    const showOrderSummaryModal = ref(false);
    
</script>
 
<template>
    <div class="flex flex-col pb-2 overflow-auto" @click="showOrderSummaryModal = true" >
        <div class="border-solid border-2 border-gray-50 relative flex flex-col items-start p-6 mt-3 bg-white rounded-lg cursor-pointer bg-opacity-90 group hover:bg-opacity-100 shadow-md" draggable="true">
            <span class="flex items-center h-6 px-3 text-xs font-semibold text-green-500 bg-green-100 rounded-full uppercase">{{transaction.order_type}}</span>
            <h4 class="mt-3 text-lg font-bold">#{{transaction.receipt_no}}</h4>
            <span class="text-xs"> {{ moment(transaction.order_at).fromNow() }}</span>
        </div>
    </div>

    <!-- ORDER SUMMARY MODAL -->
    <div class="fixed z-50 inset-0 overflow-y-auto" v-show="showOrderSummaryModal" @keydown.escape.prevent.stop="showOrderSummaryModal = false" x-transition>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
                role="dialog" aria-modal="true" aria-labelledby=""  @click.stop x-trap.noscroll.inert="">

                <form @submit.prevent="submit">
                    <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                        <button type="button" @click="showOrderSummaryModal = false"
                            class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                #{{ transaction.receipt_no }}
                            </h3>
                        </div>
                    </div>
                    <div class="text-center sm:mt-2 sm:ml-4 sm:text-left">
                        <span class="mt-4 font-bold" v-if="transaction.order_type == 'dine in'">Dine In</span>
                        <span class="mt-4 font-bold" v-else>Take Out</span>
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
                            <table>
                            <tr class="relative mb-2" v-for="(orderItem) in transaction.details" :key="orderItem.id">
                                <td>{{ orderItem.product_name }}</td>
                                <td>x {{ orderItem.quantity }}</td>
                            </tr>
                            <div class="mt-6"></div>
                            </table>

                        </div>
                    </div>

                    <div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <InfoButton type="button" class="ml-4" @click="showOrderSummaryModal = false">Close</InfoButton>
                    </div>
                </form>
                                
            </div>
        </div>
    </div>
    <!-- ORDER SUMMARY MODAL -->
</template>