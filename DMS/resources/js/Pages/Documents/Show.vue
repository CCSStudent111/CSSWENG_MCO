<template>
    <!-- <AppLayout>
        
         Header 
        <div class="d-flex align-center justify-between custom-title">
            <h1 class="text-h4 font-weight-bold mb-2">Document "{{ document.name }}"</h1>

            <div class="d-flex gap-2">
                <Link :href="route('documents.logs', document.id)">
                <v-btn color="secondary" size="small" variant="flat" prepend-icon="mdi-history">
                    Logs
                </v-btn>
                </Link>

                <Link :href="route('documents.edit', document.id)">
                <v-btn color="primary" size="small" variant="flat" prepend-icon="mdi-pencil">
                    Edit
                </v-btn>
                </Link>
            </div>
        </div>

        <div class="d-flex align-center text-grey-darken-1 mb-1" style="font-size: 0.9rem;">
            <v-icon class="me-1" size="18" color="grey">mdi-calendar</v-icon>
            Issued at: {{ dayjs(document.issued_at).format('MMMM D, YYYY') }}
        </div>

         Type 
        <div class="d-flex align-center text-grey-darken-1 mb-1" style="font-size: 0.9rem;">
            <v-icon class="me-1" size="18" color="grey">mdi-file-document-outline</v-icon>
            Type: {{ document.type?.name ?? 'N/A' }}
        </div>

         Created By 
        <div class="d-flex align-center text-grey-darken-1" style="font-size: 0.9rem;">
            <v-icon class="me-1" size="18" color="grey">mdi-account</v-icon>
            Created by: {{ document.creator?.username ?? 'Unknown' }}
        </div>
        <v-divider class="mb-6" />

         Summary Section 
        <section class="mb-6">
            <h2 class="text-subtitle-1 font-weight-medium mb-2">Summary</h2>
            <p class="text-body-1 text-grey-darken-2" style="white-space: pre-line;">{{ document.summary }}</p>
        </section>

         Tags 
        <section class="mb-6">
            <h2 class="text-subtitle-1 font-weight-medium mb-2">Tags</h2>
            <div class="d-flex flex-wrap">
                <v-chip v-for="tag in document.tags" :key="tag.id" class="me-2 mb-2" color="grey-lighten-3" size="small"
                    label variant="flat">
                    {{ tag.name }}
                </v-chip>
            </div>
        </section>

         Pages 
        <section v-if="document.pages?.length" class="mb-6">
            <h2 class="text-subtitle-1 font-weight-medium mb-2">Attached Pages</h2>
            <v-list density="compact" nav>
                <a v-for="page in document.pages" :key="page.id" :href="`/storage/${page.file_path}`" target="_blank"
                    class="v-list-item rounded-lg" style="display: block;">
                    <v-list-item>
                        <template #prepend>
                            <v-icon color="primary">mdi-file-document-outline</v-icon>
                        </template>
<v-list-item-title>{{ page.original_name }}</v-list-item-title>
</v-list-item>
</a>
</v-list>
</section>
</AppLayout> -->
    <AppLayout>
        <div class="create-page-wrapper mt-2">
            <v-row class="fill-height">
                <v-col cols="7">
                    <v-card class="fill-height pa-2 elevation-3">
                        <v-card-title>Attached Pages</v-card-title>
                        <v-card-text>
                            <v-list v-if="document.pages?.length" density="compact" nav>
                                <v-list-item v-for="page in document.pages" :key="page.id"
                                    :href="`/storage/${page.file_path}`" target="_blank" rounded link>
                                    <template #prepend>
                                        <v-icon color="primary">mdi-file-document-outline</v-icon>
                                    </template>
                                    <v-list-item-title>{{ page.original_name }}</v-list-item-title>
                                </v-list-item>
                            </v-list>
                            <div v-else class="text-grey">No pages attached.</div>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="5">
                    <v-card class="fill-height pa-2 elevation-3">
                        <v-card-title>Document Details</v-card-title>
                        <v-card-text>
                            <v-text-field label="Status" :model-value="document.status" readonly disabled
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
                            
                            <v-btn v-if="document.status === 'pending'">
                                REJECT
                            </v-btn>
                            <v-btn v-if="document.status === 'pending'">
                                APPROVE
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
import AppLayout from '@/Layouts/AppLayout.vue'
import dayjs from 'dayjs'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    document: Object
})

</script>

<style scoped>
.create-page-wrapper {
    height: calc(100vh - 100px);
}
</style>
