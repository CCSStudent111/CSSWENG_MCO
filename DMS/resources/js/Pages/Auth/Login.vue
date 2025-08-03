<template>
    <v-container class="center-height d-flex" fluid>
        <v-row align="center" justify="center">
            <v-spacer></v-spacer>
            <v-col class="d-flex justify-center">
                <v-sheet elevation="5" height="370" width="400" rounded="lg">
                    <div class="in-title mt-5">Sign In</div>
                    <form @submit.prevent="form.post(route('login.submit'))" class="pa-2">
                        <v-text-field v-model="form.username" hide-details="auto" label="Username" name="username"
                            variant="outlined" class="in-input" :error-messages="form.errors.username" required />
                        <v-text-field v-model="form.password" hide-details="auto" label="Password" name="password"
                            type="password" variant="outlined" class="in-input" :error-messages="form.errors.password"
                            required />

                        <v-btn class="mt-3 in-button" color="black" type="submit" :loading="form.processing" block
                            rounded>
                            Log In
                        </v-btn>
                    </form>
                </v-sheet>
            </v-col>
            <v-spacer></v-spacer>
        </v-row>
    </v-container>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
const form = useForm({
    username: '',
    password: ''
})
</script>

<style scoped></style>