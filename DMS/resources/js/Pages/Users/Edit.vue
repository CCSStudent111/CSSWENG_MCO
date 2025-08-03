<template>
  <AppLayout title="Edit User">
    <div class="custom-title mb-6">Edit User</div>

    <v-form @submit.prevent="submit" class="form-grid">
      <v-text-field
        v-model="form.username"
        label="Username"
        :error-messages="form.errors.username"
        required
      />

      <v-text-field
        v-model="form.email"
        label="Email"
        :error-messages="form.errors.email"
        required
      />

      <v-text-field
        v-model="form.first_name"
        label="First Name"
        :error-messages="form.errors.first_name"
      />

      <v-text-field
        v-model="form.middle_name"
        label="Middle Name"
        :error-messages="form.errors.middle_name"
      />

      <v-text-field
        v-model="form.last_name"
        label="Last Name"
        :error-messages="form.errors.last_name"
      />

      <v-text-field
        v-model="form.suffix"
        label="Suffix"
        :error-messages="form.errors.suffix"
      />

      <v-text-field
        v-model="form.date_of_birth"
        label="Date of Birth"
        type="date"
        :error-messages="form.errors.date_of_birth"
      />

      <v-select
        v-model="form.department_id"
        :items="departments"
        item-value="id"
        item-title="name"
        label="Department"
        :error-messages="form.errors.department_id"
      />

      <v-checkbox
        v-model="form.is_admin"
        label="Is Admin?"
        :error-messages="form.errors.is_admin"
      />

      <v-checkbox
        v-model="form.is_manager"
        label="Is Manager?"
        :error-messages="form.errors.is_manager"
      />

      <div class="d-flex justify-end gap-2 mt-4">
        <v-btn type="submit" color="primary" variant="flat">Save</v-btn>
        <Link :href="route('users.index')">
          <v-btn variant="outlined">Cancel</v-btn>
        </Link>
      </div>
    </v-form>
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  user: Object,
  departments: Array
})

const form = useForm({
  id: props.user.id,
  username: props.user.username,
  email: props.user.email,
  first_name: props.user.first_name,
  middle_name: props.user.middle_name,
  last_name: props.user.last_name,
  suffix: props.user.suffix,
  date_of_birth: props.user.date_of_birth,
  department_id: props.user.department_id,
  is_admin: props.user.is_admin,
  is_manager: props.user.is_manager,
})

function submit() {
  form.put(route('users.update', props.user.id))
}
</script>

<style scoped>
.custom-title {
  font-size: 2rem;
  font-weight: 600;
}
.form-grid {
  display: grid;
  gap: 16px;
  max-width: 600px;
}
</style>
