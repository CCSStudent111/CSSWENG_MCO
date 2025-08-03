<template>
    <AppLayout>
        <div class="custom-title mb-4">Create User</div>
        <v-form @submit.prevent="openConfirm">
            <v-text-field
                label="Username"
                v-model="user.username"
                required
                density="compact"
                class="mb-3"
            />
            <div v-if="errors.username" class="text-red-500 text-sm">{{ errors.username }}</div>

            <v-text-field
                label="Email"
                v-model="user.email"
                type="email"
                required
                density="compact"
                class="mb-3"
            />
            <div v-if="errors.email" class="text-red-500 text-sm">{{ errors.email }}</div>

            <v-text-field
                label="Password"
                v-model="user.password"
                type="password"
                required
                density="compact"
                class="mb-3"
            />
            <div v-if="errors.password" class="text-red-500 text-sm">{{ errors.password }}</div>

            <v-text-field
                label="Confirm Password"
                v-model="user.password_confirmation"
                type="password"
                required
                density="compact"
                class="mb-3"
            />
            <div v-if="errors.password_confirmation" class="text-red-500 text-sm">{{ errors.password_confirmation }}</div>

            <v-text-field
                label="First Name"
                v-model="user.first_name"
                required
                density="compact"
                class="mb-3"
            />
            <div v-if="errors.first_name" class="text-red-500 text-sm">{{ errors.first_name }}</div>

            <v-text-field
                label="Middle Name"
                v-model="user.middle_name"
                density="compact"
                class="mb-3"
            />

            <v-text-field
                label="Last Name"
                v-model="user.last_name"
                required
                density="compact"
                class="mb-3"
            />
            <div v-if="errors.last_name" class="text-red-500 text-sm">{{ errors.last_name }}</div>

            <v-text-field
                label="Date of Birth"
                v-model="user.date_of_birth"
                type="date"
                density="compact"
                class="mb-3"
            />
            <div v-if="errors.date_of_birth" class="text-red-500 text-sm">{{ errors.date_of_birth }}</div>

            <v-select
                v-model="user.department_id"
                :items="departments"
                item-title="name"
                item-value="id"
                label="Department"
                required
                density="compact"
                class="mb-3"
                clearable
            />
            <div v-if="errors.department_id" class="text-red-500 text-sm">{{ errors.department_id }}</div>

            <v-select
                v-model="user.role"
                :items="roleOptions"
                label="Role"
                required
                density="compact"
                class="mb-3"
                clearable
            />
            <div v-if="errors.role" class="text-red-500 text-sm">{{ errors.role }}</div>

            <v-btn
                type="submit"
                color="primary"
                variant="flat"
                :disabled="!isFormValid"
            >
                Create User
            </v-btn>

            <Link :href="route('users.index')">
                <v-btn class="ml-2" color="secondary" variant="text">Cancel</v-btn>
            </Link>
        </v-form>

        <v-dialog v-model="confirmDialog" max-width="400">
            <v-card>
                <v-card-title>Confirm</v-card-title>
                <v-card-text>Are you sure you want to create this user?</v-card-text>
                <v-card-actions>
                    <v-btn color="grey" variant="text" @click="confirmDialog = false">Cancel</v-btn>
                    <v-btn color="primary" variant="flat" @click="submit">Confirm</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const { props } = usePage()
const errors = props.errors

defineProps({
    departments: Array
})

const user = ref({
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
    first_name: '',
    middle_name: '',
    last_name: '',
    date_of_birth: '',
    department_id: null,
    role: '',
    is_admin: false,
    is_manager: false
})

watch(() => user.value.role, (newRole) => {
    user.value.is_manager = (newRole === 'Manager')
})

const roleOptions = ['Employee', 'Manager']

const confirmDialog = ref(false)

const isFormValid = computed(() =>
    user.value.username &&
    user.value.email &&
    user.value.password &&
    user.value.password_confirmation &&
    user.value.first_name &&
    user.value.last_name &&
    user.value.date_of_birth &&
    user.value.department_id &&
    user.value.role
)

function openConfirm() {
    confirmDialog.value = true
}

function submit() {
    router.post(route('users.store'), user.value)
    confirmDialog.value = false
}
</script>
