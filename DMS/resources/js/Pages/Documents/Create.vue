<template>
    <AppLayout>
        <v-card class="pa-6 mt-2" elevation="2" rounded="lg" max-width="700" style="height: 100%;">
            <h2 class="text-h6 font-weight-bold mb-4">Upload Document</h2>

            <!-- Show selected file list -->
            <div v-if="selectedFiles.length > 0" class="mb-4">
                <div class="mb-3 text-body-1 font-weight-medium">Selected Files:</div>

                <div class="d-flex flex-column gap-3">
                    <div v-for="(file, index) in selectedFiles" :key="index"
                        class="d-flex align-center justify-between border rounded px-4 py-3" style="width: 100%;">
                        <div class="flex-grow-1 me-4">
                            <div class="text-subtitle-2 text-truncate">{{ file.name }}</div>
                            <div class="text-caption">{{ (file.size / 1024).toFixed(1) }} KB</div>
                        </div>
                        <v-btn icon size="small" color="error" variant="text" @click="removeFile(index)">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </div>
                </div>
            </div>
            <!-- Drag-and-drop upload area -->
            <v-sheet v-else class="d-flex flex-column align-center justify-center text-center" rounded border
                border-dashed color="grey-lighten-4" @dragover.prevent="dragOver = true"
                @dragleave.prevent="dragOver = false" @drop.prevent="handleDrop"
                :class="{ 'bg-blue-lighten-5': dragOver }" height="475">
                <v-icon size="48" color="primary">mdi-cloud-upload-outline</v-icon>

                <div class="text-caption mt-2 mb-1">
                    Supported formats: JPEG, PNG, GIF, MP4, PDF, PSD, AI, Word, PPT
                </div>

                <div class="text-body-2 font-weight-medium mb-3">
                    Drag & drop files or
                    <span class="text-primary text-decoration-underline cursor-pointer" @click="triggerFileInput">
                        Browse
                    </span>
                </div>

                <input ref="fileInput" type="file" multiple class="d-none" @change="handleFileChange"
                    accept=".jpg,.jpeg,.png,.gif,.mp4,.pdf,.psd,.ai,.doc,.docx,.ppt,.pptx" />
            </v-sheet>
        </v-card>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'

const fileInput = ref(null)
const selectedFiles = ref([])
const dragOver = ref(false)

const removeFile = (index) => {
    selectedFiles.value.splice(index, 1)
}

const triggerFileInput = () => {
    fileInput.value?.click()
}

const handleFileChange = (e) => {
    selectedFiles.value = Array.from(e.target.files)
}

const handleDrop = (e) => {
    dragOver.value = false
    const files = e.dataTransfer?.files
    if (files && files.length > 0) {
        selectedFiles.value = Array.from(files)
    }
}
</script>

<style scoped>
.cursor-pointer {
    cursor: pointer;
}
</style>
