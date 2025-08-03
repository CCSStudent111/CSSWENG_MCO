<template>
  <AppLayout>
    <div class="custom-title mb-6">Edit User</div>

    <v-form @submit.prevent="submit" ref="formRef">
      <v-container fluid>
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field v-model="form.username" label="Username" required />
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field v-model="form.email" label="Email" required type="email" />
          </v-col>

          <v-col cols="12" md="4">
            <v-text-field v-model="form.first_name" label="First Name" />
          </v-col>
          <v-col cols="12" md="4">
            <v-text-field v-model="form.middle_name" label="Middle Name" />
          </v-col>
          <v-col cols="12" md="4">
            <v-text-field v-model="form.last_name" label="Last Name" />
          </v-col>

          <v-col cols="12" md="6">
            <v-text-field v-model="form.suffix" label="Suffix" />
          </v-col>

          <v-col cols="12" md="6">
            <v-text-field v-model="form.date_of_birth" label="Date of Birth" type="date" />
          </v-col>

          <v-col cols="12" md="6">
            <v-select
              v-model="form.department_id"
              :items="departments"
              item-value="id"
              item-title="name"
              label="Department"
              required
            />
          </v-col>

          <v-col cols="12" md="3">
            <v-switch v-model="form.is_admin" label="Administrator" />
          </v-col>
          <v-col cols="12" md="3">
            <v-switch v-model="form.is_manager" label="Manager" />
          </v-col>
        </v-row>
      </v-container>

      <div class="d-flex justify-end mt-6">
        <v-btn color="secondary" variant="outlined" @click="goBack" class="mr-2">
          Cancel
        </v-btn>
        <v-btn color="primary" variant="flat" type="submit">
          Save Changes
        </v-btn>
      </div>
    </v-form>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  user: Object,
  departments: Array,
})

const formRef = ref(null)

const form = useForm({
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

function goBack() {
  window.history.back()
}
</script>

<style scoped>
.custom-title {
  font-size: 2.5rem;
  font-weight: bold;
}
</style>
