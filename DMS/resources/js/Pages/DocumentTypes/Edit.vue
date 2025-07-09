<template>
  <AppLayout>
    <div class="custom-title mb-4">Edit Document Type</div>
    <v-form @submit.prevent="submit">
      <v-text-field
        v-model="form.name"
        label="Document Type Name"
        :error-messages="form.errors.name"
        required
        density="compact"
        class="mb-3"
      />
      <v-btn type="submit" color="primary" variant="flat">Save Changes</v-btn>
      <Link :href="route('documentTypes.index')">
        <v-btn class="ml-2" color="secondary" variant="text">Cancel</v-btn>
      </Link>
    </v-form>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({
  documentType: Object
})

const documentType = props.documentType ?? {}

const form = useForm({
  name: documentType.name || ''
})

function submit() {
  form.put(route('documentTypes.update', documentType.id))
}
</script>