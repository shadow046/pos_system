<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import swal from 'sweetalert';

defineProps({
    status: String,
    expired_at: String,
    email: String
});

const form = useForm({
    otp: '',
});

const submit = () => {
    form.post(route('device.verify'), {
        onFinish: () => form.reset('otp'),
    });
};


const isNumber = (evt) => {  
      const charCode = evt.which ? evt.which : evt.keyCode;  
      if (  
        charCode > 31 &&  
        (charCode < 48 || charCode > 57) &&  
        charCode !== 46  
      ) {  
        evt.preventDefault();  
      }  
};

const resend = () => {
    axios.post(route('resend.otp'))
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
};
</script>

<template>
    <GuestLayout>
        <h1 class="text-xl font-semibold text-center">Device Verification</h1>
        <div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0 mt-4">
            <svg class="h-6 w-6 text-center" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path>
            </svg>
            <h1 class="text-lg font-bold text-center mt-2">Email</h1>
        </div>
        <p class="text-sm mt-4">We just sent your authentication code via email to {{email}}. The code will expire at {{expired_at}} PST.</p>
        
        <Head title="Device Verification" />
        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="mt-4">
            <div>
                <InputLabel for="otp" value="Device Verification Code" />

                <TextInput
                    id="otp"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.otp"
                    required
                    autofocus
                    placeholder="XXXXXX"
                    @keypress="isNumber($event)"
                />

                <InputError class="mt-2" :message="form.errors.otp" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Verify
                </PrimaryButton>
            </div>
        </form>

        <hr class="mt-8"/>
        <div class=" mt-4">
            <label class="flex items-center">
                <span class="ml-2 text-sm text-gray-600">Having trouble verifying via email?</span>
            </label>
            <ul>
                <li>
                    <a href="#" class="ml-2 text-sm text-blue-700 mt-2" @click="resend()">Re-send the authentication code</a>
                </li>
            </ul>
        </div>
    </GuestLayout>
</template>
