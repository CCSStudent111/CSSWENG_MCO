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

                                <v-alert v-if="pageErrors.length" type="error" class="mt-2" dense text>
                                    <div v-for="(error, index) in pageErrors" :key="index">{{ error }}</div>
                                </v-alert>

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
                                                <a :href="'/storage/' + page.file_path" class="text-blue-600 underline">
                                                    {{ page.original_name }}
                                                </a>
                                            </td>
                                            <td>
                                                <v-btn icon variant="text" size="small" @click="openEditDialog(page)">
                                                    <v-icon>mdi-pencil</v-icon>
                                                </v-btn>
                                                <v-btn icon variant="text" size="small" @click="reuploadPage(page)">
                                                    <v-icon>mdi-upload</v-icon>
                                                </v-btn>
                                                <v-btn icon variant="text" size="small" @click="deletePage(page)">
                                                    <v-icon color="red">mdi-delete</v-icon>
                                                </v-btn>
                                                <v-btn icon variant="text" size="small" @click="downloadPage(page)">
                                                    <v-icon>mdi-download</v-icon>
                                                </v-btn>
                                            </td>
                                        </tr>
                                    </tbody>
                                </v-table>

                                <v-dialog v-model="editDialog" max-width="500px">
                                    <v-card>
                                        <v-card-title>Edit File Name</v-card-title>
                                        <v-card-text>
                                            <v-text-field v-model="editedPage.nameWithoutExtension"
                                                label="New File Name" variant="outlined"
                                                density="compact" />
                                            <div class="text-caption text-grey">File extension: .{{ editedPage.extension
                                            }}</div>
                                        </v-card-text>
                                        <v-card-actions class="justify-end">
                                            <v-btn variant="text" @click="editDialog = false">Cancel</v-btn>
                                            <v-btn color="primary" variant="flat" @click="saveEditedName">Save</v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="5">
                        <v-card class="fill-height pa-2 elevation-3">
                            <div class="d-flex align-center justify-space-between mb-1" style="gap: 16px;">
                                <v-card-title class="pa-0">Document Details</v-card-title>
                                <div style="min-width: 180px;">
                                    <v-select v-model="form.target_type" :items="['General', 'Client']" label="Category"
                                        :error-messages="form.errors.target_type" required density="compact"
                                        variant="outlined" />
                                </div>
                            </div>
                            <v-card-text>
                                <v-select v-if="form.target_type === 'Client'" v-model="form.user_id"
                                    :items="clientsWithName" item-title="full_name" item-value="id"
                                    label="Select Client" :error-messages="form.errors.user_id" density="compact"
                                    variant="outlined" clearable />
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
import { computed } from 'vue'
import { ref, reactive } from 'vue'
import dayjs from 'dayjs'

const props = defineProps({
    document: Object,
    documentTypes: Array,
    clients: Array
})

const form = useForm({
    name: props.document.name || '',
    document_type_id: props.document.type?.id ?? null,
    issued_at: props.document.issued_at ? dayjs(props.document.issued_at).format('YYYY-MM-DD')
        : '',
    summary: props.document.summary || '',
    tags: props.document.tags?.map(tag => tag.name) || [],
    pages: [],
    target_type: props.document.clients?.length ? 'Client' : 'General',
    user_id: props.document.clients?.[0]?.id ?? null,
})

const editDialog = ref(false)
const editedPage = reactive({
    id: null,
    original_name: '',
    nameWithoutExtension: '',
    extension: ''
})

function openEditDialog(page) {
    const lastDotIndex = page.original_name.lastIndexOf('.')
    const base = lastDotIndex !== -1 ? page.original_name.slice(0, lastDotIndex) : page.original_name
    const ext = lastDotIndex !== -1 ? page.original_name.slice(lastDotIndex + 1) : ''

    editedPage.id = page.id
    editedPage.original_name = page.original_name
    editedPage.nameWithoutExtension = base
    editedPage.extension = ext
    editDialog.value = true
}

function saveEditedName() {
    const newName = `${editedPage.nameWithoutExtension.trim()}.${editedPage.extension}`

    router.put(route('document-pages.rename', editedPage.id), {
        original_name: newName,
    }, {
        onSuccess: () => {
            const page = props.document.pages.find(p => p.id === editedPage.id)
            if (page) page.original_name = newName

            editDialog.value = false
        },
        onError: () => {
            alert('Failed to rename file.')
        }
    })
}

const pageErrors = computed(() => {
    const errors = form.errors.pages || []
    for (const key in form.errors) {
        if (key.startsWith('pages.') && key !== 'pages') {
            errors.push(form.errors[key])
        }
    }
    return errors
})

const clientsWithName = computed(() => {
    return props.clients.map(client => ({
        id: client.id,
        full_name: `${client.name} (${client.branch})`
    }))
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
