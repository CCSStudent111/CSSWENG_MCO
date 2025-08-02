<template>
    <AppLayout>
        <div class="create-page-wrapper mt-2">
            <v-form @submit.prevent="submit">
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
                                <v-card-subtitle class="mt-4 mb-2 font-weight-bold">Existing Document
                                    Pages</v-card-subtitle>
                                <v-table>
                                    <thead>
                                        <tr>
                                            <th class="text-left">File Name</th>
                                            <th class="text-left">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(page, index) in props.document.pages" :key="page.id">
                                            <td>
                                                <a :href="'/storage/' + page.file_path"
                                                    class="text-blue-600 underline">
                                                    {{ page.original_name }}
                                                </a>
                                            </td>
                                            <td>
                                                <v-btn icon variant="text" size="small" @click="downloadPage(page)">
                                                    <v-icon>mdi-download</v-icon>
                                                </v-btn>
                                                <v-btn icon variant="text" size="small" @click="reuploadPage(page)">
                                                    <v-icon>mdi-upload</v-icon>
                                                </v-btn>
                                                <v-btn icon variant="text" size="small" @click="deletePage(page)">
                                                    <v-icon color="red">mdi-delete</v-icon>
                                                </v-btn>
                                            </td>
                                        </tr>
                                    </tbody>
                                </v-table>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="5">
                        <v-card class="fill-height pa-2 elevation-3">
                            <v-card-title>Document Details</v-card-title>
                            <v-card-text>
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
                                <v-text-field label="Created By" :model-value="document.creator?.username ?? 'Unknown'"
                                    readonly disabled density="compact" variant="outlined" />
                            </v-card-text>
                            <v-card-actions class="justify-end">
                                <Link :href="route('documents.show', props.document.id)">
                                <v-btn variant="text" color="secondary">
                                    <v-icon start>mdi-arrow-left</v-icon> Back
                                </v-btn>
                                </Link>

                                <v-btn color="primary" variant="flat" @click="submit">
                                    <v-icon start>mdi-content-save</v-icon> Update Document
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-form>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, Link, router } from '@inertiajs/vue3'
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

function submit() {
    form.post(route('documents.update', props.document.id), {
        forceFormData: true,
        onSuccess: () => {
            // success logic
        },
        onError: () => {
            // optional: scroll to first error
        },
        preserveScroll: true,
        headers: {
            'X-HTTP-Method-Override': 'PUT', // method spoofing for Laravel
        }
    })
}

function downloadPage(page) {
    window.open(route('document-pages.download', page.id), '_blank');
}

function reuploadPage(page) {
    const input = document.createElement('input');
    input.type = 'file';

    input.addEventListener('change', () => {
        if (!input.files.length) return;

        const formData = new FormData();
        formData.append('page', input.files[0]);

        formData.append('_method', 'PUT'); 

        router.post(route('document-pages.update', page.id), formData, {
            forceFormData: true,
            onSuccess: () => {
                location.reload(); 
            },
            onError: () => {
                alert('Reupload failed.');
            },
        });
    });

    input.click();
}

function deletePage(page) {
    if (!confirm(`Are you sure you want to delete ${page.original_name}?`)) return;

    router.delete(route('document-pages.destroy', page.id), {
        onSuccess: () => {
            location.reload();
        },
        onError: () => {
            alert('Delete failed.');
        }
    });
}
</script>

<style scoped>
.create-page-wrapper {
    height: calc(100vh - 100px);
}
</style>
