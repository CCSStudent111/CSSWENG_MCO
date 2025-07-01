<template>
    <AppLayout>
        <div class="custom-title mb-4">Create Hospital</div>

        <v-form>
            <v-text-field label="Hospital Name" v-model="hospital.name" required></v-text-field>
            <v-text-field label="Branch" v-model="hospital.branch" required></v-text-field>
            <v-btn color="primary" @click="submit">Create Hospital</v-btn>
        </v-form>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const hospital = ref({
    name: '',
    branch: ''
});

function submit() {
    router.post('/hospitals', hospital.value);
}
</script>
