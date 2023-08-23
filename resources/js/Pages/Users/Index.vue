<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Table from '@/Components/Table.vue';
    import User from '@/Components/User.vue';
    import SimplePagination from '@/Components/SimplePagination.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import TextInput from '@/Components/TextInput.vue';
    import SelectInput from '@/Components/SelectInput.vue';
    import InfoButton from '@/Components/InfoButton.vue';
    import { Head, useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';

    defineProps(['users', 'roles']);

    const showModalAdd = ref(false);

    const form = useForm({
        first_name: '',
        middle_name: '',
        last_name: '',
        email: '',
        role: '',
        status: ''
    });

    const submit = () => {
        form.post(route('users.store'), {
            onSuccess: () => {
                form.reset();
                location.reload();
            },
        });
    };
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 lg:ml-60 md:ml-64">
                <Table :title="'Users'" :description="'Showing users'">
                    <template #action>
                        <PrimaryButton class="ml-4" @click="showModalAdd = true">
                            Add user
                        </PrimaryButton>
                    </template>
                    <div class="w-full overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Full Name</th>
                                <th class="px-4 py-3">Role</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y" v-if="users.data.length > 0">
                            <User v-for="user in users.data"
                                :key="user.id"
                                :user="user"
                                :roles="roles">
                            </User>
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
                    <SimplePagination :data="users" />
                </Table>
            </div>
        </div>
    </AuthenticatedLayout>


    <!-- ADD USER MODAL -->
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
                                Create User
                            </h3>
                        </div>
                    </div>
                    <div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
                            
                            <div class="relative mb-2">
                                <InputLabel for="first_name" value="First Name" />
                                <TextInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" required autofocus autocomplete="first_name" />
                                <InputError class="mt-2" :message="form.errors.first_name" />
                            </div>
                            <div class="relative mb-2">
                                <InputLabel for="middle_name" value="Middle Name" />
                                <TextInput id="middle_name" type="text" class="mt-1 block w-full" v-model="form.middle_name" autofocus autocomplete="middle_name" />
                                <InputError class="mt-2" :message="form.errors.middle_name" />
                            </div>
                            <div class="relative mb-2">
                                <InputLabel for="last_name" value="Last Name" />
                                <TextInput id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required autofocus autocomplete="last_name" />
                                <InputError class="mt-2" :message="form.errors.last_name" />
                            </div>
                            <div class="relative mb-2">
                                <InputLabel for="email" value="Email" />
                                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus autocomplete="email" />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>
                            <div class="relative mb-2">
                                <InputLabel for="role" value="Role" />
                                <SelectInput id="role" class="mt-1 block w-full" v-model="form.role" required autofocus autocomplete="role">
                                    <option disabled value="">Select Role</option>
                                    <option v-for="role in roles" :key="role"> {{role }}</option>
                                </SelectInput>
                                <InputError class="mt-2" :message="form.errors.role" />
                            </div>
                            <div class="relative mb-2">
                                <InputLabel for="status" value="Status" />
                                <SelectInput id="status" class="mt-1 block w-full" v-model="form.status" required autofocus autocomplete="status">
                                    <option disabled value="">Select Status</option>
                                    <option value="active">Enable</option>
                                    <option value="inactive">Disable</option>
                                </SelectInput>
                                <InputError class="mt-2" :message="form.errors.status" />
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
    <!-- ADD USER MODAL -->
</template>
