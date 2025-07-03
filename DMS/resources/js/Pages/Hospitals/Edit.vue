<template>
  <AppLayout>
    <div class="custom-title mb-4">Edit Hospital</div>
    <v-form @submit.prevent="submit">
      <v-text-field
        v-model="form.name"
        label="Hospital Name"
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
        <v-btn type="submit" color="primary" variant="flat">Save Changes</v-btn>
        <Link :href="route('hospitals.index')">
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
  hospital: Object
})

const form = ref({
  name: props.hospital?.name ?? '',
  branch: props.hospital?.branch ?? ''
})

function submit() {
  router.put(route('hospitals.update', props.hospital.id), form.value)
}
</script>

