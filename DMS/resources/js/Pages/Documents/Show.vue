<template>
    <AppLayout>
        <div class="create-page-wrapper mt-2">
            <v-row class="fill-height">
                <v-col cols="7">
                    <v-card class="fill-height pa-2 elevation-3">
                        <v-card-title class="d-flex justify-between align-center">
                            <span>Attached Pages</span>
                            <v-btn 
                                v-if="selectedPage"
                                @click="downloadFile"
                                color="primary"
                                variant="outlined"
                                size="small"
                                prepend-icon="mdi-download"
                            >
                                Download
                            </v-btn>
                        </v-card-title>
                        <v-card-text class="pa-0">
                            <!-- File List -->
                            <v-list v-if="document.pages?.length" density="compact" nav class="mb-2">
                                <v-list-item 
                                    v-for="page in document.pages" 
                                    :key="page.id"
                                    @click="selectPage(page)"
                                    :class="{ 'bg-blue-lighten-5': selectedPage?.id === page.id }"
                                    rounded
                                    link
                                >
                                    <template #prepend>
                                        <v-icon 
                                            :color="selectedPage?.id === page.id ? 'primary' : 'grey'"
                                            :icon="getFileIcon(page.file_path)"
                                        ></v-icon>
                                    </template>
                                    <v-list-item-title>{{ page.original_name }}</v-list-item-title>
                                    <template #append>
                                        <v-btn
                                            :href="`/storage/${page.file_path}`"
                                            target="_blank"
                                            icon="mdi-open-in-new"
                                            variant="text"
                                            size="small"
                                            @click.stop
                                        ></v-btn>
                                    </template>
                                </v-list-item>
                            </v-list>
                            <div v-else class="text-grey pa-4">No pages attached.</div>
                            
                            <!-- File Viewer -->
                            <v-divider v-if="selectedPage"></v-divider>
                            <div v-if="selectedPage" class="file-viewer pa-4">
                                <div class="text-subtitle-2 mb-2">Preview: {{ selectedPage.original_name }}</div>
                                
                                <!-- PDF Viewer -->
                                <div v-if="isPDF(selectedPage.file_path)" class="pdf-container">
                                    <iframe
                                        :src="`/storage/${selectedPage.file_path}`"
                                        width="100%"
                                        height="400"
                                        type="application/pdf"
                                    >
                                        <p>Your browser does not support PDFs. 
                                            <a :href="`/storage/${selectedPage.file_path}`" target="_blank">Download the PDF</a>
                                        </p>
                                    </iframe>
                                </div>
                                
                                <!-- Image Viewer -->
                                <div v-else-if="isImage(selectedPage.file_path)" class="image-container">
                                    <v-img
                                        :src="`/storage/${selectedPage.file_path}`"
                                        :alt="selectedPage.original_name"
                                        max-height="400"
                                        contain
                                        class="mx-auto"
                                    >
                                        <template v-slot:placeholder>
                                            <v-row class="fill-height ma-0" align="center" justify="center">
                                                <v-progress-circular indeterminate color="primary"></v-progress-circular>
                                            </v-row>
                                        </template>
                                    </v-img>
                                </div>
                                
                                <!-- Unsupported File Type -->
                                <div v-else class="text-center pa-8">
                                    <v-icon size="64" color="grey">mdi-file-question</v-icon>
                                    <div class="text-h6 mt-2">Preview not available</div>
                                    <div class="text-body-2 text-grey mb-4">This file type cannot be previewed</div>
                                    <v-btn
                                        :href="`/storage/${selectedPage.file_path}`"
                                        target="_blank"
                                        color="primary"
                                        variant="outlined"
                                        prepend-icon="mdi-download"
                                    >
                                        Download File
                                    </v-btn>
                                </div>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="5">
                    <v-card class="fill-height pa-2 elevation-3">
                        <v-card-title>Document Details</v-card-title>
                        <v-card-text>
                            <v-text-field v-if="document.status === 'pending'" label="Status" :model-value="document.status" readonly disabled
                                density="compact" variant="outlined" class="mb-4" />

                            <v-text-field label="Document Name" :model-value="document.name" readonly disabled
                                density="compact" variant="outlined" class="mb-4" />

                            <v-text-field label="Document Type" :model-value="document.type?.name || 'N/A'" readonly
                                disabled density="compact" variant="outlined" class="mb-4" />

                            <v-textarea label="Summary" :model-value="document.summary" readonly disabled
                                density="compact" variant="outlined" rows="4" class="mb-4" />

                            <v-text-field label="Issued At"
                                :model-value="document.issued_at ? dayjs(document.issued_at).format('MMMM D, YYYY') : 'N/A'"
                                readonly disabled density="compact" variant="outlined" />

                            <div class="mb-4">
                                <label class="v-label v-label--active">Tags</label>
                                <div class="d-flex flex-wrap mt-1">
                                    <v-chip v-for="tag in document.tags" :key="tag.id" color="grey-lighten-3"
                                        size="small" label variant="flat" class="me-2 mb-2">
                                        {{ tag.name }}
                                    </v-chip>
                                    <span v-if="!document.tags?.length" class="text-grey">No tags</span>
                                </div>
                            </div>

                            <v-text-field label="Created By" :model-value="document.creator?.username ?? 'Unknown'"
                                readonly disabled density="compact" variant="outlined" />

                            <v-text-field v-if="document.status === 'approved'" label="Approved By"
                                :model-value="document.approver?.username ?? 'Unknown'" readonly disabled
                                density="compact" variant="outlined" class="mb-4" />
                        </v-card-text>

                        <v-card-actions class="justify-end">
                            <v-btn v-if="document.status === 'pending'" color="red" variant="flat" size="small"
                                prepend-icon="mdi-close" @click="rejectDocument">
                                Reject
                            </v-btn>

                            <v-btn v-if="document.status === 'pending'" color="green" variant="flat" size="small"
                                prepend-icon="mdi-check" @click="approveDocument">
                                Approve
                            </v-btn>

                            <Link :href="route('documents.edit', props.document.id)">
                            <v-btn color="primary" variant="flat" size="small" prepend-icon="mdi-pencil">
                                Edit Document
                            </v-btn>
                            </Link>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import dayjs from 'dayjs'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    document: Object
})

const selectedPage = ref(null)

// File type detection
const isPDF = (filePath) => {
    return /\.pdf$/i.test(filePath)
}

const isImage = (filePath) => {
    return /\.(jpg|jpeg|png|gif|bmp|webp|svg)$/i.test(filePath)
}

const getFileIcon = (filePath) => {
    if (isPDF(filePath)) return 'mdi-file-pdf-box'
    if (isImage(filePath)) return 'mdi-file-image'
    return 'mdi-file-document-outline'
}

// File selection
const selectPage = (page) => {
    selectedPage.value = page
}

// Download file
const downloadFile = () => {
    if (selectedPage.value) {
        const link = document.createElement('a')
        link.href = `/storage/${selectedPage.value.file_path}`
        link.download = selectedPage.value.original_name
        link.click()
    }
}

// Auto-select first file if available
if (props.document.pages?.length > 0) {
    selectedPage.value = props.document.pages[0]
}

const approveDocument = () => {
    if (confirm('Are you sure you want to approve this document?')) {
        router.post(route('documents.approve', props.document.id))
    }
}

const rejectDocument = () => {
    if (confirm('Are you sure you want to reject and delete this document?')) {
        router.delete(route('documents.reject', props.document.id))
    }
}
</script>

<style scoped>
.create-page-wrapper {
    height: calc(100vh - 100px);
}

.file-viewer {
    border-top: 1px solid #e0e0e0;
}

.pdf-container iframe {
    border: 1px solid #ddd;
    border-radius: 4px;
}

.image-container {
    border: 1px solid #ddd;
    border-radius: 4px;
    overflow: hidden;
}
</style>
