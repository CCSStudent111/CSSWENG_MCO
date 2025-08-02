<template>
  <AppLayout>
    <div class="custom-title mb-4">Edit Client</div>
    <v-form @submit.prevent="submit">
      <v-text-field
        v-model="form.name"
        label="Client Name"
        required
        density="compact"
        class="mb-3"
      ></v-text-field>
      <v-text-field
        v-model="form.branch"
        label="Branch"
        required
        density="compact"
        class="mb-3"
      ></v-text-field>
      <v-text-field
        v-model="form.address"
        label="Address"
        required
        density="compact"
        class="mb-3"
      ></v-text-field>
      <v-select
        v-model="client.type"
        :items="typeOptions"
        label="Type"
        required
        density="compact"
        class="mb-3"
        clearable
      /><v-btn type="submit" color="primary" variant="flat">Save Changes</v-btn>
        <Link :href="route('clients.index')">
        <v-btn class="ml-2" color="secondary" variant="text">Cancel</v-btn>
        </Link>
    </v-form>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  client: Object
})

const form = ref({
  name: props.client?.name ?? '',
  branch: props.client?.branch ?? '',
  address: props.client?.address ?? '',
  type: props.client?.type ?? ''
})

function submit() {
  router.put(route('clients.update', props.client.id), form.value)
}
</script>

