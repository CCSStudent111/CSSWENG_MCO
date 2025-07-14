<template>
    <div class="container-fluid d-flex justify-content-center align-items-center center-height">
        <div class="d-flex flex-column in-card col-4">
            <div class="in-title">Sign In</div>
            <form @submit.prevent="form.post(route('login.submit'))" class="d-flex flex-column mt-5 row-gap-3">
                <div class="d-flex flex-column">
                    <label for="username" class="in-fieldname">Username</label>
                    <input type="text" class="in-input" id="username" v-model="form.username" />
                    <span v-if="form.errors.username" class="text-error">{{ form.errors.username }}</span>
                </div>
                <div class="d-flex flex-column">
                    <label for="password" class="in-fieldname">Password</label>
                    <input type="password" class="in-input" id="password" v-model="form.password" />
                    <span v-if="form.errors.password" class="text-error">{{ form.errors.password }}</span>
                </div>
                <v-btn class="mt-3" color="black" type="submit" :loading="form.processing" block rounded>
                    Log In
                </v-btn>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    username: '',
    password: ''
})
</script>

<style scoped></style>
