<template>
  <AppLayout>
    <div class="custom-title mb-4">Create Department</div>
    <v-form @submit.prevent="submit">
      <v-text-field
        v-model="form.name"
        label="Department Name"
        :error-messages="form.errors.name"
        required
        density="compact"
        class="mb-3"
      />
      <v-btn type="submit" color="primary" variant="flat">Create</v-btn>
      <Link :href="route('departments.index')">
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
  form.post(route('departments.store'))
}
</script>