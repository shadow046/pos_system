<script setup>
    import InputLabel from '@/Components/InputLabel.vue';
    import TextInput from '@/Components/TextInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import { useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import InfoButton from '@/Components/InfoButton.vue';
    import InputError from '@/Components/InputError.vue';

    const props = defineProps(['orderItem', 'index', 'orderItems', 'errors']);
    const showModalAdd = ref(false);
    
    const form = useForm({
        'id': props.orderItem.id,
        'name': props.orderItem.name,
        'price': props.orderItem.price,
        'image': props.orderItem.image !== null ? props.orderItem.photo : 'default-image.jpg',
        'sub_total': props.orderItem.quantity*props.orderItem.price,
        'quantity': props.orderItem.quantity,
    });

    const removeItem = (index) => {
        props.orderItems.splice(index, 1);
    }

    const reduceQuantity = (item) => {
        props.orderItems.map((product) => {
            if (product.id === item.id) {
                product.quantity--;
                product.sub_total = product.quantity*item.price;
            }
        });
    }

    const addQuantity = (item) => {
        props.orderItems.map((product) => {
            if (product.id === item.id) {
                product.quantity++;
                product.sub_total = product.quantity*item.price;
            }
        });
    }

    const confirm = (item) => {
        props.orderItems.map((product) => {
            if (product.id === item.id) {
                product.quantity = form.quantity;
                product.sub_total = product.quantity*item.price;
            }
        });
        showModalAdd.value = false;
    }

</script>
 
<template>
    <div class="flex flex-row justify-between items-center mt-4">
        <div class="flex flex-row items-center w-2/5">
        <img :src="orderItem.image" class="w-10 h-10 object-cover rounded-md" alt="">
        <span class="ml-4 font-semibold text-sm">{{ orderItem.name }}</span>
        </div>
        <div class="w-32 flex justify-between">
        <span class="px-3 py-1 rounded-md bg-gray-300 cursor-pointer" @click="reduceQuantity(orderItem)" v-if="orderItem.quantity > 1">-</span>
        <span class="px-3 py-1 rounded-md bg-red-300 text-white cursor-pointer" @click="removeItem(index)" v-else>x</span>
        <span class="font-semibold mx-4 cursor-pointer" @click="showModalAdd = true">{{orderItem.quantity}}</span>
        <span class="px-3 py-1 rounded-md bg-gray-300 cursor-pointer" @click="addQuantity(orderItem)">+</span>
        </div>
        <div class="font-semibold text-lg w-16 text-center">
            ₱{{orderItem.sub_total.toLocaleString(undefined, { minimumFractionDigits: 2 })}}
        </div>
    </div>
    <InputError class="mt-2 text-xs" :message="props.errors" />


    <!-- CUSTOM QUANTITY MODAL -->
    <div class="fixed z-50 inset-0 overflow-y-auto" v-show="showModalAdd" @keydown.escape.prevent.stop="showModalAdd = false" x-transition>
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
                        <button type="button" @click="showModalAdd = false"
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
                                <InputLabel for="quantity" value="Enter Quantity" />
                                <TextInput id="quantity" type="number" min="0" step="1" v-model="form.quantity" class="mt-1 block w-full" required autofocus autocomplete="name" />
                            </div>

                        </div>
                    </div>

                    <div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <PrimaryButton class="ml-4" type="button" @click="confirm(orderItem)">
                            Confirm
                        </PrimaryButton>
                        <InfoButton type="button" class="ml-4" @click="showModalAdd = false">Cancel</InfoButton>
                    </div>
                </form>
                                
            </div>
        </div>
    </div>
    <!-- CUSTOM QUANTITY MODAL -->
</template>