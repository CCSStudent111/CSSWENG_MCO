<template>
  <AppLayout>
    <v-container>
      <v-row>
        <!-- Client Details -->
        <v-col cols="12" md="6">
          <v-card>
            <v-card-title>
              <span class="text-h5">Client Details</span>
            </v-card-title>
            <v-card-text>
              <v-list>
                <v-list-item>
                  <v-list-item-title><strong>ID:</strong> {{ client.id }}</v-list-item-title>
                </v-list-item>
                <v-list-item>
                  <v-list-item-title><strong>Name:</strong> {{ client.name }}</v-list-item-title>
                </v-list-item>
                <v-list-item>
                  <v-list-item-title><strong>Branch:</strong> {{ client.branch }}</v-list-item-title>
                </v-list-item>
                <v-list-item>
                  <v-list-item-title><strong>Address:</strong> {{ client.address }}</v-list-item-title>
                </v-list-item>
                <v-list-item>
                  <v-list-item-title><strong>Type:</strong> {{ client.type }}</v-list-item-title>
                </v-list-item>
                <v-list-item>
                  <v-list-item-title>
                    <strong>Date Added:</strong> {{ formatDate(client.created_at) }}
                  </v-list-item-title>
                </v-list-item>
                <v-list-item>
                  <v-list-item-title>
                    <strong>Last Updated:</strong> {{ formatDate(client.updated_at) }}
                  </v-list-item-title>
                </v-list-item>
              </v-list>
            </v-card-text>
            <v-card-actions>
              <Link :href="route('clients.index')">
              <v-btn color="primary" variant="flat">Back to List</v-btn>
              </Link>
            </v-card-actions>
          </v-card>
        </v-col>

        <!-- Associated Documents -->
        <v-col cols="12" md="6">
          <v-card>
            <v-card-title>
              <span class="text-h5">Associated Documents</span>
            </v-card-title>
            <v-card-text>
              <v-list v-if="documents.length" density="compact" nav>
                <v-list-item v-for="doc in documents" :key="doc.id" :href="route('documents.show', doc.id)" link rounded
                  >
                  <template #prepend>
                    <v-icon color="primary">mdi-file-document-outline</v-icon>
                  </template>
                  <v-list-item-content>
                    <v-list-item-title>
                      {{ doc.title || doc.name || 'Untitled Document' }}
                    </v-list-item-title>
                    <v-list-item-subtitle>
                      {{ formatDate(doc.created_at) }}
                    </v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
              </v-list>
              <div v-else class="text-grey">No documents found.</div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import dayjs from 'dayjs'

const props = defineProps({
  client: Object,
  documents: {
    type: Array,
    default: () => []
  }
})

const formatDate = (date) => dayjs(date).format('MM/DD/YYYY')
</script>
