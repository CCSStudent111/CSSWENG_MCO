<template>
  <AppLayout>
    <div class="custom-title mb-4">Edit Department</div>
    <v-form @submit.prevent="submit">
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
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  department: Object
})

const form = ref({
  name: props.department?.name ?? ''
})

function submit() {
  router.put(route('departments.update', props.department.id), form.value)
}
</script>