<template>
    <AppLayout>
        <div class="d-flex justify-content-end p-4">
            <v-btn color="red" @click="logout" rounded="pill">
                Logout
            </v-btn>
        </div>
    </AppLayout>
</template>
<script setup>
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const logout = () => {
  router.post(route('logout'))
}
</script>

<style scoped></style>