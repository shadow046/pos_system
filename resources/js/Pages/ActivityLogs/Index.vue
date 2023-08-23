<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Table from '@/Components/Table.vue';
import Role from '@/Components/Role.vue';
import SimplePagination from '@/Components/SimplePagination.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InfoButton from '@/Components/InfoButton.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import moment from 'moment';

defineProps(['user', 'activity_logs', 'last_login', 'last_logout']);

const formatDate = (value) => {
    return moment(value).format("MMMM DD, YYYY h:m:s A")
}

const formatReadableDate = (value) => {
    return moment(value).fromNow()
}
</script>

<template>
    <Head title="Activity Logs" />

    <AuthenticatedLayout>
        
        <div class="py-12 mt-10 max-w-7xl mx-auto sm:px-6 lg:px-8 lg:ml-80 md:ml-72 shadow sm:rounded-lg">
            <main class=" w-full pb-8">
                <h1 class="font-bold">{{user.profile.first_name}} {{user.profile.middle_name}} {{user.profile.last_name}} Activity logs</h1>
                <p class="text-xs font-bold mt-4">Last login:
                    <span v-if="last_login != null">
                        {{ formatDate(last_login.created_at) }}
                    </span>
                    <span v-else>Not yet accessed.</span>
                </p>
                <p class="text-xs font-bold mt-2">Last logout:
                    <span v-if="last_logout != null">
                        {{ formatDate(last_logout.created_at) }}
                    </span>
                    <span v-else>Never logout.</span>
                </p>
                <ol class="relative border-l-2 mt-4">
                    <li class="mb-10 ml-4" v-for="(activityLog, index) in activity_logs" :key="index">
                        <div class="absolute w-4 h-4 rounded-full -left-1.5 border border-white border-gray-400 bg-gray-400">&nbsp;&nbsp;</div>
                        <time class="mb-1 text-sm font-bold leading-none text-gray-800">{{index}}</time>
                        <ul>
                            <li v-for="activity in activityLog" :key="activity.id">

                                <div class="flex flex-col justify-start  sm:flex-row sm:pb-0 text-sm font-normal text-gray-500 mt-2">
                                    <p class="text-sm font-normal text-gray-500 mt-2">{{activity.description}} </p>
                                    <span class="text-xs text-slate-400 ml-4 mt-2">{{formatReadableDate(activity.created_at)}}</span>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ol>
            </main>
            
        </div>
    </AuthenticatedLayout>
</template>
