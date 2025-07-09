<template>
  <AppLayout>
    <div class="custom-title mb-4">Edit Department</div>
    <v-form v-if="department" @submit.prevent="submit">
      <v-text-field
        v-model="form.name"
        label="Department Name"
        :error-messages="form.errors.name"
        required
        density="compact"
        class="mb-3"
      />
      <v-btn type="submit" color="primary" variant="flat">Save Changes</v-btn>
      <Link :href="route('departments.index')">
        <v-btn class="ml-2" color="secondary" variant="text">Cancel</v-btn>
      </Link>
    </v-form>
    <div v-else>
      <v-alert type="error" text>
        Department not found or data not loaded.
      </v-alert>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({
  department: Object
})

const department = props.department ?? {}

const form = useForm({
  name: department.name || ''
})

function submit() {
  form.put(route('departments.update', department.id))
}
</script>