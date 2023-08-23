<script setup>
    import Dropdown from '@/Components/Dropdown.vue';
    import DropdownLink from '@/Components/DropdownLink.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import TextInput from '@/Components/TextInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import { useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import InfoButton from '@/Components/InfoButton.vue';
    import SuccessBadge from '@/Components/Badges/Small/Rounded/Success.vue';
    import DangerBadge from '@/Components/Badges/Small/Rounded/Danger.vue';
    import DefaultBadge from '@/Components/Badges/Small/Default.vue';
    import swal from 'sweetalert';
    import axios from 'axios';
    import VueMultiselect from 'vue-multiselect'

    const props = defineProps(['product', 'categories']);
    const showModalEdit = ref(false);
    
    const form = useForm({
        name: props.product.name,
        description: props.product.description,
        price: props.product.price,
        quantity: props.product.quantity,
        image: '',
        categories: props.product.categories,
    });

    const submit = () => {
        form.post(route('products.update', props.product.uuid), {
            onSuccess: () => {
                form.reset();
                location.reload();
            },
        });
    };

    function destroy(id) {
        swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            className: "p-8",
            buttons: {
                cancel: true,
                confirm: 'Yes, delete it!',
            },
            dangerMode: true,
        }).then((res) => {
            if(res)
            {
                axios.delete(route('products.delete', props.product.uuid))
                    .then(res => {
                        form.reset();
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

</script>
 
<template>
    <tr class="text-gray-700  text-sm">
        <td class="px-4 py-3">
            <div class="flex flex-row items-center w-2/5">
                <img :src="product.photo" class="w-8 h-8 object-cover rounded-md" alt="" v-if="product.image != null">
                <span class="ml-4 font-semibold text-sm">{{product.name}}</span>
            </div>
        </td>
        <td class="px-4 py-3 text-xs">
            <span v-for="category in product.categories" :key="category.id">
                <DefaultBadge >
                    {{category.name}}
                </DefaultBadge> &nbsp;
            </span>
        </td>
        <td class="px-4 py-3">
            PHP {{product.price}}
        </td>
        <td class="px-4 py-3">
            {{product.quantity}}
        </td>
        <td class="px-4 py-3 text-xs capitalize">
            <SuccessBadge v-if="product.status === 'available'">
                {{product.status}}
            </SuccessBadge>
            <DangerBadge v-else>
                {{product.status}}
            </DangerBadge>
        </td>
        <td class="px-4 py-3 text-sm">
            <button class="truncate" @click="showModalEdit = true">
                <span class="text-sky-500 hover:text-sky-700 font-medium mr-4">Edit</span>
            </button>
            <button class="truncate">
                <span class="text-red-500 hover:text-red-700 font-medium" @click="destroy(product.uuid)">Remove</span>
            </button>
        </td>
    </tr>

    <!-- EDIT PRODUCT MODAL -->
    <div class="fixed z-50 inset-0 overflow-y-auto" v-show="showModalEdit" @keydown.escape.prevent.stop="showModalEdit = false" x-transition>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
                role="dialog" aria-modal="true" aria-labelledby=""  @click.stop x-trap.noscroll.inert="">
                <form @submit.prevent="submit"  enctype="multipart/form-data">
                    <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                        <button type="button" @click="showModalEdit = false"
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
                                Edit Product
                            </h3>
                        </div>
                    </div>
                    <div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
                            
                            <div class="relative mb-2">
                                <InputLabel for="name" value="Name" />
                                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>
                            <div class="relative mb-2">
                                <InputLabel for="description" value="Description" />
                                <TextInput id="description" type="text" class="mt-1 block w-full" v-model="form.description" autofocus autocomplete="description" />
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>
                            <div class="relative mb-2">
                                <InputLabel for="price" value="Price" />
                                <TextInput id="price" type="number" min="0" step="0.01" class="mt-1 block w-full" v-model="form.price" required autofocus autocomplete="price" />
                                <InputError class="mt-2" :message="form.errors.price" />
                            </div>
                            <div class="relative mb-2">
                                <InputLabel for="quantity" value="Quantity" />
                                <TextInput id="quantity" type="number" min="0" step="1" class="mt-1 block w-full" v-model="form.quantity" required autofocus autocomplete="quantity" />
                                <InputError class="mt-2" :message="form.errors.quantity" />
                            </div>
                            <div class="relative mb-2">
                                <InputLabel for="category" value="Category" />
                                <VueMultiselect
                                    v-model="form.categories"
                                    :options="props.categories"
                                    :multiple="true"
                                    :close-on-select="true"
                                    placeholder="Select category"
                                    label="name"
                                    track-by="name"
                                    class="border border-1 rounded-md"
                                />
                                <InputError class="mt-2" :message="form.errors.categories" />
                            </div>
                            <div class="relative mb-2">
                                <InputLabel for="image" value="Image" />
                                <TextInput id="image" type="file" @input="form.image = $event.target.files[0]" class="mt-1 block w-full" v-model="form.image" autofocus />
                                <InputError class="mt-2" :message="form.errors.image" />
                            </div>

                        </div>
                    </div>

                    <div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <PrimaryButton class="ml-4">
                            Update
                        </PrimaryButton>
                        <InfoButton type="button" class="ml-4" @click="showModalEdit = false">Cancel</InfoButton>
                    </div>
                </form>
                                
            </div>
        </div>
    </div>
    <!-- EDIT PRODUCT MODAL -->
</template>