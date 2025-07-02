<template>
    <AppLayout>
        <!-- Title -->
        <div class="custom-title mb-6">Editing Document {{ document.name }}</div>

        <!-- Name -->
        <v-text-field v-model="form.name" label="Document Name" variant="outlined" density="comfortable"
            class="mb-4 field-wide" />

        <!-- Issued At + Type -->
        <v-text-field v-model="form.issued_at" type="date" label="Issued At" density="compact" variant="underlined"
            prepend-inner-icon="mdi-calendar" class="field-medium mb-4 small-text" />

        <!-- Document Type -->
        <v-select v-model="form.document_type_id" :items="documentTypes" item-title="name" item-value="id"
            label="Document Type" density="compact" variant="underlined" prepend-inner-icon="mdi-file-document-outline"
            class="field-medium mb-4 small-text" />

        <!-- Creator -->
        <div class="text-grey-darken-1 mb-4 d-flex align-center small-text">
            <v-icon class="me-1" size="18" color="grey">mdi-account</v-icon>
            <span>Created by: {{ props.document.creator.username }}</span>
        </div>

        <v-divider class="mb-4" />

        <!-- Summary -->
        <v-textarea v-model="form.summary" label="Summary" auto-grow density="comfortable" variant="outlined"
            class="mb-6 field-xwide" />

        <!-- Tags -->
        <v-combobox v-model="form.tags" :items="[]" chips multiple closable-chips label="Tags"
            placeholder="Enter tags and press Enter" hide-selected hide-no-data variant="outlined"
            class="mb-6 field-wide" />

        <!-- Save Button -->
        <v-btn color="primary" size="small" variant="flat" prepend-icon="mdi-content-save" @click="submit">
            Save
        </v-btn>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    document: Object,
    documentTypes: Array
})

const form = reactive({
    name: props.document.name,
    document_type_id: props.document.type?.id ?? null,
    issued_at: props.document.issued_at ?? '',
    summary: props.document.summary ?? '',
    tags: props.document.tags?.map(tag => tag.name) ?? []
})

function submit() {
    const payload = {
        name: form.name,
        summary: form.summary,
        document_type_id: form.document_type_id,
        issued_at: form.issued_at,
        tags: form.tags
    }

    router.put(route('documents.update', props.document.id), payload)
}
</script>

<style scoped>
.custom-title {
    font-size: 2.5rem;
    font-weight: bold;
}

.field-wide {
    max-width: 500px;
}

.field-medium {
    max-width: 250px;
}

.field-xwide {
    max-width: 700px;
}

.small-text {
    font-size: 0.9rem;
}
</style>
