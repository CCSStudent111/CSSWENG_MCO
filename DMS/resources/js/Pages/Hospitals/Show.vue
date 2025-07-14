<template>
  <AppLayout>
    <v-container>
      <v-row>
        <!-- Hospital Details -->
        <v-col cols="12" md="6">
          <v-card>
            <v-card-title>
              <span class="text-h5">Hospital Details</span>
            </v-card-title>
            <v-card-text>
              <v-list>
                <v-list-item>
                  <v-list-item-title><strong>ID:</strong> {{ hospital.id }}</v-list-item-title>
                </v-list-item>
                <v-list-item>
                  <v-list-item-title><strong>Name:</strong> {{ hospital.name }}</v-list-item-title>
                </v-list-item>
                <v-list-item>
                  <v-list-item-title><strong>Branch:</strong> {{ hospital.branch }}</v-list-item-title>
                </v-list-item>
                <v-list-item>
                  <v-list-item-title><strong>Type:</strong> {{ hospital.type }}</v-list-item-title>
                </v-list-item>
                <v-list-item>
                  <v-list-item-title>
                    <strong>Date Added:</strong> {{ dayjs(hospital.created_at).format('MM/DD/YYYY') }}
                  </v-list-item-title>
                </v-list-item>
                <v-list-item>
                  <v-list-item-title>
                    <strong>Last Updated:</strong> {{ dayjs(hospital.updated_at).format('MM/DD/YYYY') }}
                  </v-list-item-title>
                </v-list-item>
              </v-list>
            </v-card-text>
            <v-card-actions>
              <Link :href="route('hospitals.index')">
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
              <v-list>
                <v-list-item v-if="!documents.length">
                  <v-list-item-title>No documents found.</v-list-item-title>
                </v-list-item>
                <v-list-item v-for="doc in documents" :key="doc.id">
                  <v-list-item-title>
                    {{ doc.title || doc.name || 'Untitled Document' }}
                  </v-list-item-title>
                  <v-list-item-subtitle>
                    {{ dayjs(doc.created_at).format('MM/DD/YYYY') }}
                  </v-list-item-subtitle>
                  <template #append>
                    <Link :href="route('documents.show', doc.id)">
                      <v-btn icon size="small" variant="text">
                        <v-icon>mdi-eye</v-icon>
                      </v-btn>
                    </Link>
                  </template>
                </v-list-item>
              </v-list>
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
  hospital: Object,
  documents: {
    type: Array,
    default: () => []
  }
})
</script>