<script setup>
    import Dropdown from '@/Components/Dropdown.vue';
    import DropdownLink from '@/Components/DropdownLink.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import TextInput from '@/Components/TextInput.vue';
    import SelectInput from '@/Components/SelectInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import SuccessBadge from '@/Components/Badges/Small/Rounded/Success.vue';
    import DangerBadge from '@/Components/Badges/Small/Rounded/Danger.vue';
    import { useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import InfoButton from '@/Components/InfoButton.vue';
    import swal from 'sweetalert';
    import axios from 'axios';

    const props = defineProps(['user', 'roles']);
    const showModalEdit = ref(false);
    
    const form = useForm({
        first_name: props.user.profile.first_name,
        middle_name: props.user.profile.middle_name,
        last_name: props.user.profile.last_name,
        email: props.user.email,
        role: props.user.roles[0].name,
        status: props.user.status
    });

    const submit = () => {
        form.put(route('users.update', props.user.uuid), {
            onSuccess: () => {
                form.reset();
                location.reload();
            },
        });
    };

    const viewActivity = () => {
        return window.location.href = route('logs.activities.index', props.user.uuid);
    }

    function resend() {
        swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            className: "p-8",
            confirmButtonColor: '#3085d6',
            buttons: {
                cancel: true,
                confirm: 'Yes, resend it!',
            },
            dangerMode: true,
        }).then((res) => {
            if(res)
            {
                axios.post(route('users.password.resend', props.user.uuid))
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
    <tr class="text-gray-700 text-sm">
        <td class="px-4 py-3">
            {{user.email}}
        </td>
        <td class="px-4 py-3">
            {{user.profile.first_name}}
            {{user.profile.middle_name}}
            {{user.profile.last_name}}
        </td>
        <td class="px-4 py-3 capitalize">
            {{user.roles[0].name}}
        </td>
        <td class="px-4 py-3 capitalize">
            <SuccessBadge v-if="user.status === 'active'">
                {{user.status}}
            </SuccessBadge>
            <DangerBadge v-else>
                {{user.status}}
            </DangerBadge>
        </td>
        <td class="px-4 py-3 text-sm">
            <button class="truncate" @click="showModalEdit = true">
                <span class="text-sky-500 hover:text-sky-700 font-medium mr-4">Edit</span>
            </button>
            <button class="truncate" @click="resend()">
                <span class="text-green-500 hover:text-green-700 font-medium mr-4">Resend</span>
            </button>
            <button class="truncate" @click="viewActivity()">
                <span class="text-indigo-500 hover:text-indigo-700 font-medium mr-4">View Activity</span>
            </button>
        </td>
    </tr>

    <!-- EDIT USER MODAL -->
    <div class="fixed z-50 inset-0 overflow-y-auto" v-show="showModalEdit" @keydown.escape.prevent.stop="showModalEdit = false" x-transition>
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
                                Edit User
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
                                    <option v-for="role in props.roles" :key="role"> {{role }}</option>
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
                            Update
                        </PrimaryButton>
                        <InfoButton type="button" class="ml-4" @click="showModalEdit = false">Cancel</InfoButton>
                    </div>
                </form>
                                
            </div>
        </div>
    </div>
    <!-- EDIT USER MODAL -->
</template>