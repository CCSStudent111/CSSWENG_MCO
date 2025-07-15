<template>
    <AppLayout>
        <v-form @submit.prevent="submit">
            <v-select
                v-model="form.hospital_id"
                :items="hospitals"
                item-title="name"
                item-value="id"
                label="Hospital"
                required
            />

            <v-text-field v-model="form.name" label="Document Name" required />

            <v-textarea v-model="form.summary" label="Summary" required />

            <v-select
                v-model="form.document_type_id"
                :items="documentTypes"
                item-title="name"
                item-value="id"
                label="Document Type"
                required
            />

            <v-text-field v-model="form.issued_at" label="Issued At" type="date" clearable />

            <v-combobox
                v-model="form.tags"
                :items="[]"
                chips
                clearable
                multiple
                label="Tags"
                placeholder="Enter tags and press Enter"
                hide-selected
                hide-no-data
            />

            <v-file-input
                v-model="form.pages"
                label="Upload Files"
                multiple
                show-size
                prepend-icon="mdi-paperclip"
            />

            <v-btn type="submit" color="primary" class="mt-4">Submit</v-btn>
        </v-form>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
    hospitals: Array,
    documentTypes: Array,
})

const form = useForm({
    hospital_id: null,
    name: '',
    summary: '',
    document_type_id: null,
    issued_at: '',
    tags: [],
    pages: [],
})

const submit = () => {
    const formData = new FormData()

    formData.append('hospital_id', form.hospital_id)
    formData.append('name', form.name)
    formData.append('summary', form.summary)
    formData.append('document_type_id', form.document_type_id)
    formData.append('issued_at', form.issued_at)

    form.tags.forEach(tag => formData.append('tags[]', tag))
    form.pages.forEach(file => formData.append('pages[]', file))

    router.post(route('hospital-documents.store'), formData, {
        forceFormData: true,
        onSuccess: () => form.reset(),
    })
}
</script>

<style scoped></style>
