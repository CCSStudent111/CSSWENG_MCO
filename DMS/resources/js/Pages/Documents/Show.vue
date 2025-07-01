<template>
    <AppLayout>
        <!-- Header -->
        <div class="d-flex align-center justify-between custom-title">
            <h1 class="text-h4 font-weight-bold mb-2">Document "{{ document.name }}"</h1>
            <v-btn color="primary" size="small" variant="flat" prepend-icon="mdi-pencil">
                Edit
            </v-btn>
        </div>

        <div class="d-flex align-center text-grey-darken-1 mb-1" style="font-size: 0.9rem;">
            <v-icon class="me-1" size="18" color="grey">mdi-calendar</v-icon>
            Issued at: {{ dayjs(document.issued_at).format('MMMM D, YYYY') }}
        </div>

        <!-- Type -->
        <div class="d-flex align-center text-grey-darken-1 mb-1" style="font-size: 0.9rem;">
            <v-icon class="me-1" size="18" color="grey">mdi-file-document-outline</v-icon>
            Type: {{ document.type?.name ?? 'N/A' }}
        </div>

        <!-- Created By -->
        <div class="d-flex align-center text-grey-darken-1" style="font-size: 0.9rem;">
            <v-icon class="me-1" size="18" color="grey">mdi-account</v-icon>
            Created by: {{ document.creator?.username ?? 'Unknown' }}
        </div>
        <v-divider class="mb-6" />

        <!-- Summary Section -->
        <section class="mb-6">
            <h2 class="text-subtitle-1 font-weight-medium mb-2">Summary</h2>
            <p class="text-body-1 text-grey-darken-2" style="white-space: pre-line;">{{ document.summary }}</p>
        </section>

        <!-- Tags -->
        <section class="mb-6">
            <h2 class="text-subtitle-1 font-weight-medium mb-2">Tags</h2>
            <div class="d-flex flex-wrap">
                <v-chip v-for="tag in document.tags" :key="tag.id" class="me-2 mb-2" color="grey-lighten-3" size="small"
                    label variant="flat">
                    {{ tag.name }}
                </v-chip>
            </div>
        </section>

        <!-- Pages -->
        <section v-if="document.pages?.length" class="mb-6">
            <h2 class="text-subtitle-1 font-weight-medium mb-2">Attached Pages</h2>
            <v-list density="compact" nav>
                <v-list-item v-for="page in document.pages" :key="page.id" :href="`/storage/${page.file_path}`"
                    target="_blank" class="rounded-lg">
                    <template #prepend>
                        <v-icon color="primary">mdi-file-document-outline</v-icon>
                    </template>
                    <v-list-item-title>{{ page.original_name }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </section>
    </AppLayout>
</template>


<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import dayjs from 'dayjs'

const props = defineProps({
    document: Object
})

</script>

<style scoped>
.custom-title {
    font-size: 2.5rem;
    font-weight: bold;
}
</style>
