<!-- LAST UPDATED BY: FRANZ -->

<template>
    <AppLayout>
        <v-form @submit.prevent="submit">
            <div class="create-page-wrapper mt-2">
                <v-row class="fill-height">
                    <v-col cols="7">
                        <v-card class="fill-height pa-2 elevation-3">
                            <v-card-title>Upload Document</v-card-title>
                            <v-card-text>
                                <v-file-upload v-model="form.pages" label="Upload Files" multiple show-size
                                    prepend-icon="mdi-paperclip" :error-messages="form.errors.pages" required
                                    clearable />
                            </v-card-text>
                        </v-card>
                    </v-col>

                    <v-col cols="5">
                        <v-card class="fill-height pa-2 elevation-3">
                            <div class="d-flex align-center justify-space-between" style="gap: 16px;">
                                <v-card-title style="padding-top: 0; padding-bottom: 0; margin: 0;">
                                    Document Details
                                </v-card-title>
                                <div style="min-width: 180px;">
                                    <v-select v-model="form.target_type" :items="['General', 'Client']"
                                        label="Category" :error-messages="form.errors.target_type" required
                                        density="compact" variant="outlined" />
                                </div>
                            </div>

                            <v-card-text>

                                <v-select v-if="form.target_type === 'Client'" v-model="form.user_id"
                                    :items="clientsWithName" item-title="full_name" item-value="id"
                                    label="Select Client" :error-messages="form.errors.user_id" density="compact"
                                    variant="outlined" autocomplete clearable />

                                <v-text-field v-model="form.name" label="Document Name"
                                    :error-messages="form.errors.name" required density="compact" variant="outlined" />

                                <v-select v-model="form.document_type_id" :items="documentTypes" item-title="name"
                                    item-value="id" label="Document Type" :error-messages="form.errors.document_type_id"
                                    required density="compact" variant="outlined" />

                                <v-textarea v-model="form.summary" label="Summary" :error-messages="form.errors.summary"
                                    required density="compact" variant="outlined" />

                                <v-combobox v-model="form.tags" :items="[]" chips multiple closable-chips clearable
                                    label="Tags" placeholder="Enter tags and press Enter" hide-selected hide-no-data
                                    :error-messages="form.errors.tags" density="compact" variant="outlined" />

                                <v-text-field v-model="form.issued_at" label="Issued At" type="date" clearable
                                    :error-messages="form.errors.issued_at" density="compact" variant="outlined" />

                                <div class="d-flex justify-end">
                                    <v-btn type="submit" color="primary" class="mt-2" :loading="form.processing"
                                        density="compact">
                                        add document
                                    </v-btn>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </div>
        </v-form>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    documentTypes: Array,
    users: Array,
    clients: Array,
})

const form = useForm({
    name: '',
    summary: '',
    document_type_id: null,
    issued_at: '',
    tags: [],
    pages: [],
    target_type: 'General',
    user_id: null,
})

const usersWithFullName = computed(() => {
    return props.users.map(user => ({
        ...user,
        full_name: `${user.first_name} ${user.last_name}`,
    }))
})

const clientsWithName = computed(() => {
    return props.clients.map(client => ({
        id: client.id,
        full_name: `${client.name} (${client.address})`,
    }))
})

const submit = () => {
    form.post(route('documents.store'), {
        forceFormData: true,
        onSuccess: () => form.reset(),
    })
}
</script>

<style scoped>
.create-page-wrapper {
    height: calc(100vh - 100px);
}
</style>
