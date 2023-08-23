<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectInput from '@/Components/SelectInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InfoButton from '@/Components/InfoButton.vue';
import SuccessBadge from '@/Components/Badges/Small/Rounded/Success.vue';
import WarningBadge from '@/Components/Badges/Small/Rounded/Warning.vue';
import DangerBadge from '@/Components/Badges/Small/Rounded/Danger.vue';
import swal from 'sweetalert';
import axios from 'axios';
import moment from 'moment';

const props = defineProps(['transaction']);
const showModalChangeStatus = ref(false);

const form = useForm({
    status: props.transaction.status
});

const isEven = (number) => {
    return number % 2 === 0;
}

const formatDate = (value) => {
    return moment(value).format("MMMM DD, YYYY h:m:s A")
}

function generateReceipt() {
    swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        className: "p-8",
        buttons: {
            cancel: true,
            confirm: 'Yes, generate receipt!',
        },
        dangerMode: true,
    }).then((res) => {
        if(res)
        {
            axios.post(route('transactions.receipt.generate', props.transaction.uuid))
                .then(res => {
                    swal({
                        icon: 'success',
                        title: 'Success!',
                        text: res.data.message,
                        className: "p-8",
                        buttons: false,
                        timer: 1500
                    }).then(e => {
                        location.reload();
                    });
                });
        }
    });
}

const viewReceipt = () => {
    return window.location.href = props.transaction.receipt.file;
}

const changeStatus = () => {
        form.put(route('transactions.update', props.transaction.uuid), {
            onSuccess: () => {
                form.reset();
                location.reload();
            },
        });
    };
</script>

<template>
    <Head title="Transactions" />

    <AuthenticatedLayout>
        <div class="py-12 mt-10 max-w-7xl mx-auto sm:px-6 lg:px-8 lg:ml-80 md:ml-72 shadow sm:rounded-lg">
            <main class=" w-full pb-8">
                <div class="flex items-center justify-between py-5 lg:py-6">
                <span class="text-xs uppercase ml-4">
                    <SuccessBadge v-if="transaction.status === 'completed'">
                        {{transaction.status}}
                    </SuccessBadge>
                    <WarningBadge v-else-if="transaction.status === 'pending' || transaction.status=== 'preparing' || transaction.status=== 'serving'">
                        {{transaction.status}}
                    </WarningBadge>
                    <DangerBadge v-else>
                        {{transaction.status}}
                    </DangerBadge>
                </span>
                    <div class="flex gap-2 ">
                    <PrimaryButton type="button" @click="generateReceipt()" v-if="transaction.status != 'void'">Generate Receipt</PrimaryButton>
                    <InfoButton type="button" @click="showModalChangeStatus = true" v-if="$page.props.auth.is_admin">Change Status</InfoButton>
                    </div>
                </div>
                <div class="flex items-center justify-between py-5 lg:py-6">
                    <h2 class="text-xl ml-4 font-bold text-slate-700 dark:text-navy-50 lg:text-2xl">
                        #{{ transaction.receipt_no }}
                    </h2>

                    <div class="flex" v-if="transaction.receipt != null && transaction.status != 'void'">
                        <button @click="viewReceipt()"
                            class="btn h-8 w-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25 sm:h-9 sm:w-9">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-1">
                    <div class="card px-5 py-2 sm:px-18">
                        <div class="flex flex-col justify-between sm:flex-row">
                            <div class="text-center sm:text-left">
                                <div class="space-y-1 pt-2 font-bold text-sm">
                                    <p>Cashier: <span>{{ transaction.user.profile.first_name }} {{ transaction.user.profile.middle_name }} {{ transaction.user.profile.last_name }}</span></p>
                                    <p>
                                        Sold At: <span>{{formatDate(transaction.sold_at)}}</span>
                                    </p>
                                    <p>
                                         <span>{{transaction.order_type.toUpperCase()}}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="my-7 h-px bg-slate-200 dark:bg-navy-500"></div>
                        <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
                            <table class="is-zebra w-full text-left">
                                <thead>
                                    <tr>
                                        <th
                                            class="whitespace-nowrap rounded-l-lg bg-slate-200 px-3 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                            Item
                                        </th>
                                        <th
                                            class="whitespace-nowrap bg-slate-200 px-3 py-3 text-right font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                            Qty
                                        </th>
                                <th
                                    class="whitespace-nowrap rounded-r-lg bg-slate-200 px-3 py-3 text-right font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                    SUBTOTAL
                                </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr  v-for="(order, index) in transaction.orders" :key="index" :class="isEven(index) ? '' : 'bg-slate-100'">
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5  rounded-l-lg">
                                            <div>
                                                <p class="font-medium">
                                                    {{order.product.name}}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="w-3/12 whitespace-nowrap px-4 py-3 text-right sm:px-5 ">
                                            {{order.quantity}}
                                        </td>
                                        <td class="w-3/12 whitespace-nowrap px-4 py-3 text-right sm:px-5  rounded-r-lg">
                                            {{ order.price }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="my-7 h-px bg-slate-200 dark:bg-navy-500"></div>

                        <div class="flex flex-col justify-end sm:flex-row">
                            <div class="mt-4 text-center sm:m-0 sm:text-right">
                                <div class="space-y-1 pt-2">
                                    <p class="text-lg text-primary font-bold mb-8">
                                        Amount Due: <span>₱ {{transaction.amount}}</span>
                                    </p>
                                    <div class="text-sm">
                                        <p>Vatable Sales : <span class="font-medium">₱ {{ transaction.sales }}</span></p>
                                        <p>VAT Amount 12 % : <span class="font-medium">₱ {{ transaction.vat }}</span></p>
                                        <p>Discount : <span class="font-medium">₱ {{ transaction.discount }}</span></p>
                                        <p>Cash : <span class="font-medium">₱ {{transaction.amount_paid}}</span></p>
                                        <p>Change : <span class="font-medium">₱ {{ transaction.change }}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </AuthenticatedLayout>


    <!-- CHANGE STATUS MODAL -->
    <div class="fixed z-50 inset-0 overflow-y-auto" v-show="showModalChangeStatus" @keydown.escape.prevent.stop="showModalChangeStatus = false" x-transition>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
                role="dialog" aria-modal="true" aria-labelledby=""  @click.stop x-trap.noscroll.inert="">
                <form @submit.prevent="changeStatus">
                    <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                        <button type="button" @click="showModalChangeStatus = false"
                            class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">Close</span>
                            <!-- Heroicon name: outline/x -->
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                Change Status
                            </h3>
                        </div>
                    </div>
                    <div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
                            
                            <div class="relative mb-2">
                                <InputLabel for="status" value="Status" />
                                <SelectInput id="status" class="mt-1 block w-full" v-model="form.status" required autofocus autocomplete="status">
                                    <option disabled value="">Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="preparing">Preparing</option>
                                    <option value="serving">Serving</option>
                                    <option value="completed">Completed</option>
                                    <option value="void">Void</option>
                                </SelectInput>
                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>

                        </div>
                    </div>

                    <div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <PrimaryButton class="ml-4">
                            Update
                        </PrimaryButton>
                        <InfoButton type="button" class="ml-4" @click="showModalChangeStatus = false">Cancel</InfoButton>
                    </div>
                </form>
                                
            </div>
        </div>
    </div>
    <!-- CHANGE STATUS MODAL -->
</template>