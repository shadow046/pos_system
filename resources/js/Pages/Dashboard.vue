<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InfoButton from '@/Components/InfoButton.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import ProductItem from '@/Components/ProductItem.vue';

defineProps(['user', 'categories']);

const showDiscountModal = ref(false);
const showOrderSummaryModal = ref(false);
const selectedCategory = ref('all');
const products = ref([]);
const orderItems = ref([]);

onMounted(() => {
    loadProducts('all');
})

const form = useForm({
    'discount_percent': 0,
    'discount': 0,
    'cash': 0,
    'change': 0,
    'order_type': 'dine in',
    'items': [],
    'amount': 0,
    'sales': 0,
    'vat': 0,
    'total_order': 0,
});

async function loadProducts(item) {
    selectedCategory.value = item;
    products.value = await (await fetch(route('api.products.get')+'?category=' + item)).json();
}

const addItem = (item) => {
    let itemExists = false;
    orderItems.value.map((product) => {
        if (product.id === item.id) {
            product.quantity++;
            product.sub_total = product.quantity*item.price;
            itemExists = true;
        }
    });
      
      if(!itemExists) {
        orderItems.value.push({
            'id': item.id,
            'name': item.name,
            'price': item.price,
            'image': item.image !== null ? item.photo : 'default-image.jpg',
            'sub_total': 1*item.price,
            'quantity': 1,
        })
      }
}

const removeAll = () => {
    form.discount_percent = 0;
    form.discount_amount = 0;
    orderItems.value.splice(0, orderItems.value.length);
}

const grandTotal = computed(() => {
    return orderItems.value.reduce((total, item) => {
        return total + (item.quantity * item.price);
    }, 0);
})

const totalOrder = computed(() => {
    return orderItems.value.reduce((total,item) => {
        return total + item.quantity;
    }, 0);
})


const subTotal = computed(() => {
    return grandTotal.value - ((grandTotal.value / 1.12) * 0.12);
})

const tax = computed(() => {
    return (grandTotal.value / 1.12) * 0.12
})

const discount = computed(() => {
    return subTotal.value * (form.discount_percent / 100)
})

const total = computed(() => {
    return grandTotal.value - discount.value
})

const change = computed(() => {
    return form.cash - total.value
})

const submit = () => {
    form.change = change.value.toFixed(2);
    form.amount = total.value.toFixed(2);
    form.sales = subTotal.value.toFixed(2);
    form.vat = tax.value.toFixed(2);
    form.discount = discount.value.toFixed(2);
    form.items = orderItems.value;
    form.total_order = totalOrder.value;
    form.post(route('transactions.store'), {
        onSuccess: () => {
            form.reset();
            location.reload();
        }
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 lg:ml-60 md:ml-64">
                <div class="flex lg:flex-row flex-col-reverse shadow-lg bg-gray-100">
                    <!-- left section -->
                    <div class="w-full lg:w-3/5 min-h-screen shadow-lg">
                    <!-- header -->
                    <div class="flex flex-row justify-between items-center px-5 mt-5">
                        <div class="text-gray-800">
                        <div class="font-bold text-xl"></div>
                        <span class="text-xs">User: <span class="font-bold">{{ user.email }}</span></span>
                        </div>
                        <div class="flex items-center">
                            <div>
                            <span
                                class="px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded"
                                >
                                Help
                            </span>
                            </div>
                        </div>
                    </div>
                    <!-- end header -->
                    <!-- categories -->
                    <div class="mt-5 flex flex-row px-5">
                        <button
                        class="px-5 py-1 rounded-2xl text-sm mr-4"
                        :class="selectedCategory == 'all' ? 'bg-yellow-500 text-white' : ''"
                        @click="loadProducts('all')"
                        >
                        All items
                        </button>
                        <button v-for="category in categories" :key="category.id"
                        @click="loadProducts(category.id)"
                         class="px-5 py-1 rounded-2xl text-sm font-semibold mr-4"
                        :class="selectedCategory == category.id ? 'bg-yellow-500 text-white' : ''">
                        {{category.name}}
                        </button>
                    </div>
                    <!-- end categories -->
                    <!-- products -->
                    <section class="text-gray-600 body-font">
                        <div class="container px-5 py-6 mx-auto">
                            <div class="flex flex-wrap -m-4">
                                <div class="lg:w-1/4 md:w-1/2 p-4 w-full cursor-pointer" v-for="product in products.data" :key="product.id" @click="addItem(product)">
                                    <a class="block relative h-32 rounded overflow-hidden">
                                    <img alt="ecommerce" class="object-cover object-center w-full h-full block" :src="product.image !== null ? product.photo : 'default-image.jpg'">
                                    </a>
                                    <div class="mt-4">
                                    <h3 class="text-gray-500 text-xs tracking-widest title-font mb-1" v-for="category in product.categories" :key="category.id">
                                        {{category.name }}
                                    </h3>
                                    <h2 class="text-gray-900 title-font text-lg font-medium">{{product.name}}</h2>
                                    <p class="mt-1">₱{{ product.price }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- end products -->
                    </div>
                    <!-- end left section -->
                    <!-- right section -->
                    <div class="w-full lg:w-2/5">
                    <!-- header -->
                    <div class="flex flex-row items-center justify-between px-5 mt-5">
                        <div class="font-bold text-xl">Current Order</div>
                        <div class="font-semibold">
                        <span class="px-4 py-2 rounded-md bg-red-100 text-red-500 cursor-pointer mr-2" @click="removeAll()">Reset</span>
                        <span class="px-4 py-2 rounded-md mr-2 cursor-pointer"
                            :class="form.order_type=='dine in' ? 'bg-green-500 text-white' : 'bg-green-200 text-gray-500'"
                             @click="form.order_type= 'dine in'">Dine In</span>
                        <span class="px-4 py-2 rounded-md cursor-pointer"
                            :class="form.order_type=='take out' ? 'bg-green-500 text-white' : 'bg-green-200 text-gray-500'"
                             @click="form.order_type= 'take out'">Take Out</span>
                        </div>
                    </div>
                    <!-- end header -->
                    <!-- order list -->
                    <div class="px-5 py-4 mt-5 overflow-y-auto h-64" v-if="orderItems.length > 0">
                        <ProductItem v-for="(orderItem, index) in orderItems"
                            :key="orderItem.id"
                            :orderItem="orderItem"
                            :index="index"
                            :orderItems="orderItems"
                            :errors="form.errors.hasOwnProperty(`items.${index}`) ? form.errors[`items.${index}`] : ''">
                        </ProductItem>
                        <InputError class="mt-2" :message="form.errors.items" />
                    </div>
                    <div class="px-5 py-4 mt-5 overflow-y-auto h-64" v-else>
                        <div class="flex flex-row justify-between items-center mb-4">
                            <div class="flex flex-row items-center w-2/5">
                                <span class="ml-4 font-semibold text-md">No items added.</span>
                            </div>
                        </div>
                    </div>
                    <!-- end order list -->
                    <!-- totalItems -->
                    <div class="px-5 mt-5">
                        <div class="py-4 rounded-md shadow-lg">
                        <div class=" px-4 flex justify-between ">
                            <span class="font-semibold text-sm">Subtotal</span>
                            <span class="font-bold">₱ {{subTotal.toFixed(2)}}</span>
                        </div>
                        <div class=" px-4 flex justify-between ">
                            <span class="font-semibold text-sm">Discount</span>
                            <span class="font-bold">- ₱ {{discount.toFixed(2)}}</span>
                        </div>
                        <div class=" px-4 flex justify-between ">
                            <span class="font-semibold text-sm">Sales Tax</span>
                            <span class="font-bold">₱ {{tax.toFixed(2)}}</span>
                        </div>
                        <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-between">
                            <span class="font-semibold text-2xl">Total</span>
                            <span class="font-bold text-2xl">₱ {{total.toFixed(2)}}</span>
                        </div>
                        </div>
                    </div>
                    <!-- end total -->
                    <!-- cash -->
                    <div class="px-5 mt-5">
                        <div class="rounded-md shadow-lg px-4 py-4">
                        <div class="flex flex-row justify-between items-center">
                            <div class="flex flex-col w-full" v-if="orderItems.length > 0">
                                <span class="uppercase text-xs font-semibold">Cash</span>
                                <TextInput type="number" min="0" step="0.01" v-model="form.cash" class="mt-1 block w-full" required autofocus autocomplete="discount" />
                                <InputError class="mt-2" :message="form.errors.cash" />
                            </div>
                            <div class="flex flex-col w-full" v-else>
                                <span class="uppercase text-xs font-semibold">Cash</span>
                                <span class="text-xl font-bold">0.00</span>
                            </div>
                        </div>
                        <div class="flex flex-row justify-between items-center mt-4">
                            <div class="flex flex-col w-full">
                                <span class="uppercase text-xs font-semibold">Change</span>
                                <span class="text-xl font-bold text-yellow-500" v-if="orderItems.length > 0 && form.cash >= total">₱ {{change.toFixed(2)}}</span>
                                <span class="text-xl font-bold text-yellow-500" v-else>₱ 0.00</span>
                            </div>
                        </div>
                        </div>
                    </div>
                    <!-- end cash -->
                    <!-- button pay-->
                    <div v-if="orderItems.length > 0" class="mb-6">
                        <div class="px-5 mt-5">
                            <button type="button" class="px-4 py-4 w-full rounded-md shadow-lg text-center bg-amber-700 text-white font-semibold hover:bg-amber-800 focus:bg-amber-900 active:bg-amber-900 cursor-pointer" @click="showDiscountModal= true">
                            Discount %
                            </button>
                        </div>
                        <div class="px-5 mt-5" v-if="form.cash >= total">
                            <button type="button" class="px-4 py-4 w-full rounded-md shadow-lg text-center bg-yellow-500 text-white font-semibold hover:bg-yellow-600 focus:bg-yellow-700 active:bg-yellow-700 cursor-pointer" @click="showOrderSummaryModal= true">
                            Pay
                            </button>
                        </div>
                        <div class="px-5 mt-5" v-else>
                            <button type="button" disabled class="px-4 py-4 w-full rounded-md shadow-lg text-center bg-yellow-400 text-white font-semibold cursor-not-allowed">
                            Pay
                            </button>
                        </div>
                    </div>
                    <div v-else class="mb-6">
                        <div class="px-5 mt-5">
                            <button type="button" disabled class="px-4 py-4 w-full rounded-md shadow-lg text-center bg-amber-600 text-white font-semibold  cursor-not-allowed">
                            Discount %
                            </button>
                        </div>
                        <div class="px-5 mt-5">
                            <button type="button" disabled class="px-4 py-4 w-full rounded-md shadow-lg text-center bg-yellow-400 text-white font-semibold cursor-not-allowed">
                            Pay
                            </button>
                        </div>
                    </div>
                    <!-- end button pay -->
                    </div>
                    <!-- end right section -->
                </div>
            </div>
        </div>

        <!-- DISCOUNT MODAL -->
        <div class="fixed z-50 inset-0 overflow-y-auto" v-show="showDiscountModal" @keydown.escape.prevent.stop="showDiscountModal = false" x-transition>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
                    role="dialog" aria-modal="true" aria-labelledby=""  @click.stop x-trap.noscroll.inert="">

                    <form>
                        <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                            <button type="button" @click="showDiscountModal = false"
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
                                    
                                </h3>
                            </div>
                        </div>
                        <div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
                                
                                <div class="relative mb-2">
                                    <InputLabel for="discount" value="Enter Discount %" />
                                    <TextInput id="discount" type="number" min="0" step="0.01" v-model="form.discount_percent" class="mt-1 block w-full" required autofocus autocomplete="discount" />
                                </div>

                            </div>
                        </div>

                        <div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <PrimaryButton class="ml-4" type="button"  @click="showDiscountModal = false">
                                Confirm
                            </PrimaryButton>
                            <InfoButton type="button" class="ml-4" @click="showDiscountModal = false">Cancel</InfoButton>
                        </div>
                    </form>
                                    
                </div>
            </div>
        </div>
        <!-- DISCOUNT MODAL -->

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
                                    Order Summary
                                </h3>
                            </div>
                        </div>
                        <div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <span class="mt-4 font-bold" v-if="form.order_type == 'dine in'">Dine In</span>
                            <span class="mt-4 font-bold" v-else>Take Out</span>
                            <div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
                                <table>
                                <tr class="relative mb-2" v-for="(orderItem) in orderItems" :key="orderItem.id">
                                    <td>{{ orderItem.name }}</td>
                                    <td>x {{ orderItem.quantity }}</td>
                                    <td>{{ orderItem.price }}</td>
                                </tr>
                                <div class="mt-6"></div>
                                <tr>
                                    <td></td>
                                    <td class="font-bold">Amount Due:</td>
                                    <td class="font-bold">₱ {{ total.toFixed(2) }}</td>
                                </tr>
                                <div class="mt-6"></div>
                                <tr>
                                    <td></td>
                                    <td class="text-sm font-bold">Cash</td>
                                    <td class="text-sm font-bold">₱ {{form.cash}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-sm font-bold">Change</td>
                                    <td class="text-sm font-bold">₱ {{change.toFixed(2)}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-sm font-bold">Vatable Sales</td>
                                    <td class="text-sm font-bold">₱ {{subTotal.toFixed(2)}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-sm font-bold">Vat Amount 12 %</td>
                                    <td class="text-sm font-bold">₱ {{tax.toFixed(2)}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-sm font-bold">Discount</td>
                                    <td class="text-sm font-bold">₱ {{discount.toFixed(2)}}</td>
                                </tr>
                                </table>

                            </div>
                        </div>

                        <div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <PrimaryButton class="ml-4" type="submit" @click="showOrderSummaryModal = false">
                                Confirm
                            </PrimaryButton>
                            <InfoButton type="button" class="ml-4" @click="showOrderSummaryModal = false">Cancel</InfoButton>
                        </div>
                    </form>
                                    
                </div>
            </div>
        </div>
        <!-- ORDER SUMMARY MODAL -->
    </AuthenticatedLayout>
</template>
