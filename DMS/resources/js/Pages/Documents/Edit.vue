<template>
    <AppLayout>
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
                        <v-card-title>Document Details</v-card-title>
                        <v-card-text>
                            <v-text-field v-model="form.name" label="Document Name" :error-messages="form.errors.name"
                                required density="compact" variant="outlined" />
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
                            <v-text-field label="Created By" :model-value="document.creator?.username ?? 'Unknown'"
                                readonly disabled density="compact" variant="outlined" />
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import dayjs from 'dayjs'

const props = defineProps({
    document: Object,
    documentTypes: Array
})

const form = useForm({
    name: props.document.name || '',
    document_type_id: props.document.type?.id ?? null,
    issued_at: props.document.issued_at ? dayjs(props.document.issued_at).format('YYYY-MM-DD')
        : '',
    summary: props.document.summary || '',
    tags: props.document.tags?.map(tag => tag.name) || [],
    pages: [],
})

</script>

<style scoped>
.create-page-wrapper {
    height: calc(100vh - 100px);
}
</style>
