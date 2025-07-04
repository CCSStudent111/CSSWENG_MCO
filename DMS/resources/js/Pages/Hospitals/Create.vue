<template>
    <AppLayout>
        <div class="custom-title mb-4">Create Hospital</div>
        <v-form @submit.prevent="openConfirm">
            <v-text-field
                label="Hospital Name"
                v-model="hospital.name"
                required
                density="compact"
                class="mb-3"
            ></v-text-field>
            <v-text-field
                label="Branch"
                v-model="hospital.branch"
                required
                density="compact"
                class="mb-3"
            ></v-text-field>
            <v-text-field
                v-model="hospital.type"
                label="Type"
                required
                density="compact"
                class="mb-3"
            />
            <v-btn
                type="submit"
                color="primary"
                variant="flat"
                :disabled="!hospital.name || !hospital.branch || !hospital.type"
            >
                Create Hospital
            </v-btn>
            <Link :href="route('hospitals.index')">
                <v-btn class="ml-2" color="secondary" variant="text">Cancel</v-btn>
            </Link>
        </v-form>
        
        <v-dialog v-model="confirmDialog" max-width="400">
            <v-card>
                <v-card-title>Confirm Add</v-card-title>
                <v-card-text>
                    Are you sure you want to add this hospital?
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="secondary" variant="text" @click="confirmDialog = false">Cancel</v-btn>
                    <v-btn color="primary" variant="flat" @click="submit">Yes, Add</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const hospital = ref({
    name: '',
    branch: '',
    type: ''
});

const confirmDialog = ref(false);

function openConfirm() {
    confirmDialog.value = true;
}

function submit() {
    confirmDialog.value = false;
    router.post('/hospitals', hospital.value);
}
</script>
