<!-- LAST UPDATED BY: FRANZ  -->

<template>
    <AppLayout>
        <v-form @submit.prevent="submit">
            <div class="create-page-wrapper mt-2">
                <v-row class="fill-height">
                    <v-col cols="7">
                        <v-card class="fill-height pa-2 elevation-3">
                            <v-card-title>
                                Upload Document
                            </v-card-title>
                            <v-card-text>
                                <v-file-upload v-model="form.pages" label="Upload Files" multiple show-size
                                    prepend-icon="mdi-paperclip" :error-messages="form.errors.pages" required clearable>
                                </v-file-upload>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="5">
                        <v-card class="fill-height pa-2 elevation-3">
                            <v-card-title>
                                Document Details
                            </v-card-title>
                            <v-card-text>
                                <v-text-field v-model="form.name" label="Document Name"
                                    :error-messages="form.errors.name" required density="compact" variant="outlined" />
                                <v-select v-model="form.document_type_id" :items="documentTypes" item-title="name"
                                    item-value="id" label="Document Type" :error-messages="form.errors.document_type_id"
                                    required density="compact" variant="outlined" />
                                <v-textarea v-model="form.summary" label="Summary" :error-messages="form.errors.summary"
                                    required density="compact" variant="outlined" />
                                <v-combobox v-model="form.tags" :items="[]" chips clearable multiple label="Tags"
                                    placeholder="Enter tags and press Enter" hide-selected hide-no-data
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
import { watch } from 'vue'

const props = defineProps({
    documentTypes: Array,
})

const form = useForm({
    name: '',
    summary: '',
    document_type_id: null,
    issued_at: '',
    tags: [],
    pages: [],
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
