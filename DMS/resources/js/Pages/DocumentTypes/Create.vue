<template>
  <AppLayout>
    <div class="custom-title mb-4">Create Document Type</div>
    <v-form @submit.prevent="submit">
      <v-text-field
        v-model="form.name"
        label="Document Type Name"
        :error-messages="form.errors.name"
        required
        density="compact"
        class="mb-3"
        variant="outlined"
      />
      <v-btn 
        type="submit" 
        color="primary" 
        variant="flat"
        :loading="form.processing"
        :disabled="form.processing"
      >
        Create
      </v-btn>
      <Link :href="route('documentTypes.index')">
        <v-btn class="ml-2" color="secondary" variant="text">Cancel</v-btn>
      </Link>
    </v-form>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
  name: ''
})

function submit() {
  form.post(route('documentTypes.store'), {
    onError: (errors) => {
      // Handle validation errors
      console.log('Validation errors:', errors)
    }
  })
}
</script>

<style scoped>
.custom-title {
  font-size: 2.5rem;
  font-weight: bold;
}
</style>