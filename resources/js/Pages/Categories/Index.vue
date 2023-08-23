<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Table from '@/Components/Table.vue';
    import Category from '@/Components/Category.vue';
    import SimplePagination from '@/Components/SimplePagination.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import TextInput from '@/Components/TextInput.vue';
    import InfoButton from '@/Components/InfoButton.vue';
    import { Head, useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';

    defineProps(['categories']);

    const showModalAdd = ref(false);

    const form = useForm({
        name: ''
    });

    const submit = () => {
        form.post(route('categories.store'), {
            onSuccess: () => {
                form.reset();
                location.reload();
            },
        });
    };
</script>

<template>
    <Head title="Categories" />

    <AuthenticatedLayout>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 lg:ml-60 md:ml-64">
                <Table :title="'Categories'" :description="'Showing categories'">
                    <template #action>
                        <PrimaryButton class="ml-4" @click="showModalAdd = true">
                            Add category
                        </PrimaryButton>
                    </template>
                    <div class="w-full overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y" v-if="categories.data.length > 0">
                            <Category v-for="category in categories.data"
                                :key="category.id"
                                :category="category">
                            </Category>
                        </tbody>
                        <tbody class="bg-white divide-y" v-else>
                            <tr class="text-gray-700">
                                <td class="px-4 py-3 text-sm  font-bold" colspan="2">
                                   There are no records as of this moment.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <SimplePagination :data="categories" />
                </Table>
            </div>
        </div>
    </AuthenticatedLayout>


    <!-- ADD CATEGORY MODAL -->
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
                                Create Category
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

                        </div>
                    </div>

                    <div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <PrimaryButton class="ml-4">
                            Create
                        </PrimaryButton>
                        <InfoButton type="button" class="ml-4" @click="showModalAdd = false">Cancel</InfoButton>
                    </div>
                </form>
                                
            </div>
        </div>
    </div>
    <!-- ADD CATEGORY MODAL -->
</template>
