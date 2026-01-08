<script setup>

import { FormLabel, PlainTextInput, VTextInput } from '@/components/form';
import PrimaryButton from '@/components/button/PrimaryButton.vue';
import { useAuth } from "@/stores/auth"
import { useRouter } from "vue-router";
import { Form } from "vee-validate";
import * as yup from "yup";

const schema = yup.object({
    email: yup.string().required().email(),
    password: yup.string().required().min(8),
});
const auth = useAuth();
const router = useRouter();

const onSubmit = async (values, { setErrors, resetForm }) => {

    try {
        const res = await auth.login(values);
        if (res.user) {
            router.push({ name: 'admin.home' });
        }
    } catch (error) {
        setErrors(error);
    }
};

</script>

<template>
    <section class="flex min-h-screen items-center justify-center px-6">
        <div class="w-full max-w-xl">
            <div class="rounded-3xl border border-primary-100 bg-white p-8 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                    Admin Panel
                </p>
                <h1 class="mb-6 text-3xl font-extrabold text-slate-900">Login</h1>
                <Form @submit="onSubmit" :validation-schema="schema" v-slot="{ errors, isSubmitting, meta }">
                    <div class="space-y-4">
                        <FormLabel>
                            Email Address
                            <VTextInput type="email" name="email" placeholder="email address" />
                        </FormLabel>
                        <FormLabel>
                            Password
                            <VTextInput type="password" name="password" placeholder="password" />
                        </FormLabel>
                        <PrimaryButton type="submit" :disabled="isSubmitting">
                            Login
                        </PrimaryButton>

                    </div>

                </Form>
            </div>
        </div>
    </section>
</template>
