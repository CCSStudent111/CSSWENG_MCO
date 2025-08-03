<template>
  <AppLayout>
    <!-- Flash Messages -->
    <v-alert
      v-if="$page.props.flash?.message"
      type="success"
      class="mb-4"
      dismissible
    >
      {{ $page.props.flash.message }}
    </v-alert>

    <v-alert
      v-if="$page.props.flash?.error"
      type="error"
      class="mb-4"
      dismissible
    >
      {{ $page.props.flash.error }}
    </v-alert>

    <!-- Password Success Message -->
    <v-alert
      v-if="passwordSuccess"
      type="success"
      class="mb-4"
      dismissible
      @click:close="passwordSuccess = false"
    >
      <v-icon left>mdi-check-circle</v-icon>
      Password updated successfully!
    </v-alert>

    <!-- Password Error Message -->
    <v-alert
      v-if="passwordError"
      type="error"
      class="mb-4"
      dismissible
      @click:close="passwordError = ''"
    >
      <v-icon left>mdi-alert-circle</v-icon>
      {{ passwordError }}
    </v-alert>

    <div class="custom-title mb-6">Profile Settings</div>

    <!-- Loading state if user is not loaded -->
    <div v-if="!user" class="text-center py-8">
      <v-progress-circular indeterminate color="primary"></v-progress-circular>
      <div class="mt-4">Loading profile...</div>
    </div>
    
    <div v-else>
        <!-- Account Information Card -->
      <v-row>
        <v-col cols="12">
          <v-card>
            <v-card-title class="text-h5 pa-6 pb-0">
              <v-icon class="mr-2">mdi-information</v-icon>
              Account Information
            </v-card-title>
            <v-card-text class="pa-6">
              <v-row>
                <v-col cols="12" md="4">
                  <div class="text-subtitle-2 text-grey-darken-1 mb-1">Account Type</div>
                  <v-chip 
                    :color="user.is_admin ? 'error' : (user.is_manager ? 'warning' : 'primary')" 
                    size="small"
                    variant="tonal"
                  >
                    <v-icon left size="16">
                      {{ user.is_admin ? 'mdi-shield-crown' : (user.is_manager ? 'mdi-account-tie' : 'mdi-account') }}
                    </v-icon>
                    {{ user.is_admin ? 'Administrator' : (user.is_manager ? 'Manager' : 'Employee') }}
                  </v-chip>
                </v-col>
                
                <v-col cols="12" md="4">
                  <div class="text-subtitle-2 text-grey-darken-1 mb-1">Department</div>
                  <div class="text-body-1">{{ user.department?.name || 'No Department' }}</div>
                </v-col>
                
                <v-col cols="12" md="4">
                  <div class="text-subtitle-2 text-grey-darken-1 mb-1">Member Since</div>
                  <div class="text-body-1">{{ formatDate(user.created_at) }}</div>
                </v-col>
              </v-row>

              <v-divider class="my-4"></v-divider>

              <div class="d-flex justify-end">
                <v-btn
                  color="error"
                  variant="outlined"
                  @click="showLogoutDialog = true"
                >
                  <v-icon left>mdi-logout</v-icon>
                  Logout
                </v-btn>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <v-row>
        <!-- Profile Information Card -->
        <v-col cols="12" md="6">
          <v-card class="mb-6">
            <v-card-title class="text-h5 pa-6 pb-0">
              <v-icon class="mr-2">mdi-account</v-icon>
              Profile Information
            </v-card-title>
            <v-card-text class="pa-6">
              <v-form @submit.prevent="updateProfile">
                <v-text-field
                  v-model="profileForm.username"
                  label="Username"
                  :error-messages="profileForm.errors.username"
                  density="compact"
                  class="mb-3"
                  variant="outlined"
                  prepend-inner-icon="mdi-account"
                />
                
                <v-text-field
                  v-model="profileForm.email"
                  label="Email"
                  type="email"
                  :error-messages="profileForm.errors.email"
                  density="compact"
                  class="mb-3"
                  variant="outlined"
                  prepend-inner-icon="mdi-email"
                />

                <v-text-field
                  v-model="profileForm.first_name"
                  label="First Name"
                  :error-messages="profileForm.errors.first_name"
                  density="compact"
                  class="mb-3"
                  variant="outlined"
                  prepend-inner-icon="mdi-account-circle"
                />

                <v-text-field
                  v-model="profileForm.last_name"
                  label="Last Name"
                  :error-messages="profileForm.errors.last_name"
                  density="compact"
                  class="mb-3"
                  variant="outlined"
                  prepend-inner-icon="mdi-account-circle"
                />

                <div class="d-flex gap-3">
                  <v-btn
                    type="submit"
                    color="primary"
                    variant="flat"
                    :loading="profileForm.processing"
                    :disabled="profileForm.processing"
                  >
                    <v-icon left>mdi-content-save</v-icon>
                    Update Profile
                  </v-btn>
                  
                  <v-btn
                    color="secondary"
                    variant="outlined"
                    @click="resetProfileForm"
                    :disabled="profileForm.processing"
                  >
                    <v-icon left>mdi-restore</v-icon>
                    Reset
                  </v-btn>
                </div>
              </v-form>
            </v-card-text>
          </v-card>
        </v-col>

        <!-- Change Password Card -->
        <v-col cols="12" md="6">
          <v-card class="mb-6">
            <v-card-title class="text-h5 pa-6 pb-0">
              <v-icon class="mr-2">mdi-lock</v-icon>
              Change Password
            </v-card-title>
            <v-card-text class="pa-6">
              <v-form @submit.prevent="updatePassword">
                <v-text-field
                  v-model="passwordForm.current_password"
                  label="Current Password"
                  :type="showCurrentPassword ? 'text' : 'password'"
                  :error-messages="passwordForm.errors.current_password"
                  density="compact"
                  class="mb-3"
                  variant="outlined"
                  prepend-inner-icon="mdi-lock"
                  :append-inner-icon="showCurrentPassword ? 'mdi-eye' : 'mdi-eye-off'"
                  @click:append-inner="showCurrentPassword = !showCurrentPassword"
                />

                <v-text-field
                  v-model="passwordForm.password"
                  label="New Password"
                  :type="showNewPassword ? 'text' : 'password'"
                  :error-messages="passwordForm.errors.password || passwordValidationErrors"
                  density="compact"
                  class="mb-3"
                  variant="outlined"
                  prepend-inner-icon="mdi-lock-plus"
                  :append-inner-icon="showNewPassword ? 'mdi-eye' : 'mdi-eye-off'"
                  @click:append-inner="showNewPassword = !showNewPassword"
                  @input="validatePassword"
                />

                <v-text-field
                  v-model="passwordForm.password_confirmation"
                  label="Confirm New Password"
                  :type="showConfirmPassword ? 'text' : 'password'"
                  :error-messages="passwordForm.errors.password_confirmation || passwordConfirmationErrors"
                  density="compact"
                  class="mb-4"
                  variant="outlined"
                  prepend-inner-icon="mdi-lock-check"
                  :append-inner-icon="showConfirmPassword ? 'mdi-eye' : 'mdi-eye-off'"
                  @click:append-inner="showConfirmPassword = !showConfirmPassword"
                  @input="validatePasswordConfirmation"
                />

                <!-- Password Requirements with Dynamic Validation -->
                <v-alert
                  type="info"
                  variant="tonal"
                  class="mb-4"
                  density="compact"
                >
                  <div class="text-body-2">
                    <strong>Password Requirements:</strong>
                    <ul class="mt-2">
                      <li :class="{ 'text-success': passwordValidation.minLength, 'text-error': passwordForm.password && !passwordValidation.minLength }">
                        <v-icon size="16" :color="passwordValidation.minLength ? 'success' : (passwordForm.password ? 'error' : 'grey')">
                          {{ passwordValidation.minLength ? 'mdi-check' : 'mdi-close' }}
                        </v-icon>
                        At least 8 characters long
                      </li>
                      <li :class="{ 'text-success': passwordValidation.hasUppercase, 'text-error': passwordForm.password && !passwordValidation.hasUppercase }">
                        <v-icon size="16" :color="passwordValidation.hasUppercase ? 'success' : (passwordForm.password ? 'error' : 'grey')">
                          {{ passwordValidation.hasUppercase ? 'mdi-check' : 'mdi-close' }}
                        </v-icon>
                        Include uppercase letters (A-Z)
                      </li>
                      <li :class="{ 'text-success': passwordValidation.hasLowercase, 'text-error': passwordForm.password && !passwordValidation.hasLowercase }">
                        <v-icon size="16" :color="passwordValidation.hasLowercase ? 'success' : (passwordForm.password ? 'error' : 'grey')">
                          {{ passwordValidation.hasLowercase ? 'mdi-check' : 'mdi-close' }}
                        </v-icon>
                        Include lowercase letters (a-z)
                      </li>
                      <li :class="{ 'text-success': passwordValidation.hasNumber, 'text-error': passwordForm.password && !passwordValidation.hasNumber }">
                        <v-icon size="16" :color="passwordValidation.hasNumber ? 'success' : (passwordForm.password ? 'error' : 'grey')">
                          {{ passwordValidation.hasNumber ? 'mdi-check' : 'mdi-close' }}
                        </v-icon>
                        Include at least one number (0-9)
                      </li>
                      <li :class="{ 'text-success': passwordValidation.hasSpecial, 'text-error': passwordForm.password && !passwordValidation.hasSpecial }">
                        <v-icon size="16" :color="passwordValidation.hasSpecial ? 'success' : (passwordForm.password ? 'error' : 'grey')">
                          {{ passwordValidation.hasSpecial ? 'mdi-check' : 'mdi-close' }}
                        </v-icon>
                        Include at least one special character (@$!%*?&)
                      </li>
                      <li :class="{ 'text-success': passwordValidation.passwordsMatch, 'text-error': passwordForm.password_confirmation && !passwordValidation.passwordsMatch }">
                        <v-icon size="16" :color="passwordValidation.passwordsMatch ? 'success' : (passwordForm.password_confirmation ? 'error' : 'grey')">
                          {{ passwordValidation.passwordsMatch ? 'mdi-check' : 'mdi-close' }}
                        </v-icon>
                        Passwords match
                      </li>
                    </ul>
                  </div>
                </v-alert>

                <div class="d-flex gap-3">
                  <v-btn
                    type="submit"
                    color="primary"
                    variant="flat"
                    :loading="passwordForm.processing"
                    :disabled="passwordForm.processing || !isPasswordValid"
                  >
                    <v-icon left>mdi-lock-reset</v-icon>
                    Update Password
                  </v-btn>
                  
                  <v-btn
                    color="secondary"
                    variant="outlined"
                    @click="resetPasswordForm"
                    :disabled="passwordForm.processing"
                  >
                    <v-icon left>mdi-restore</v-icon>
                    Reset
                  </v-btn>
                </div>
              </v-form>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </div>

    <!-- Logout Confirmation Dialog -->
    <v-dialog v-model="showLogoutDialog" max-width="400">
      <v-card>
        <v-card-title class="text-h6">
          <v-icon class="mr-2">mdi-logout</v-icon>
          Confirm Logout
        </v-card-title>
        <v-card-text>
          Are you sure you want to logout?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary" variant="text" @click="showLogoutDialog = false">
            Cancel
          </v-btn>
          <v-btn color="error" variant="flat" @click="logout">
            Logout
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, computed, watch } from 'vue'

const props = defineProps({
  user: {
    type: Object,
    default: () => null
  }
})

// Reactive variables for password visibility
const showCurrentPassword = ref(false)
const showNewPassword = ref(false)
const showConfirmPassword = ref(false)
const showLogoutDialog = ref(false)

// Password validation and feedback
const passwordSuccess = ref(false)
const passwordError = ref('')
const passwordValidationErrors = ref([])
const passwordConfirmationErrors = ref([])

// Password validation state
const passwordValidation = ref({
  minLength: false,
  hasUppercase: false,
  hasLowercase: false,
  hasNumber: false,
  hasSpecial: false,
  passwordsMatch: false
})

// Computed user with safe fallback
const user = computed(() => props.user || {})

// Computed validation
const isPasswordValid = computed(() => {
  return passwordValidation.value.minLength &&
         passwordValidation.value.hasUppercase &&
         passwordValidation.value.hasLowercase &&
         passwordValidation.value.hasNumber &&
         passwordValidation.value.hasSpecial &&
         passwordValidation.value.passwordsMatch
})

// Profile form with safe defaults
const profileForm = useForm({
  username: user.value?.username || '',
  email: user.value?.email || '',
  first_name: user.value?.first_name || '',
  last_name: user.value?.last_name || '',
})

// Password form
const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: ''
})

// Watch for user changes and update form
watch(() => props.user, (newUser) => {
  if (newUser) {
    profileForm.username = newUser.username || ''
    profileForm.email = newUser.email || ''
    profileForm.first_name = newUser.first_name || ''
    profileForm.last_name = newUser.last_name || ''
  }
}, { immediate: true })

// Password validation methods
function validatePassword() {
  const password = passwordForm.password
  
  passwordValidation.value.minLength = password.length >= 8
  passwordValidation.value.hasUppercase = /[A-Z]/.test(password)
  passwordValidation.value.hasLowercase = /[a-z]/.test(password)
  passwordValidation.value.hasNumber = /\d/.test(password)
  passwordValidation.value.hasSpecial = /[@$!%*?&]/.test(password)
  
  // Check if passwords match
  validatePasswordConfirmation()
  
  // Update validation errors
  passwordValidationErrors.value = []
  if (password && !passwordValidation.value.minLength) {
    passwordValidationErrors.value.push('Password must be at least 8 characters long')
  }
  if (password && !passwordValidation.value.hasUppercase) {
    passwordValidationErrors.value.push('Password must include uppercase letters')
  }
  if (password && !passwordValidation.value.hasLowercase) {
    passwordValidationErrors.value.push('Password must include lowercase letters')
  }
  if (password && !passwordValidation.value.hasNumber) {
    passwordValidationErrors.value.push('Password must include at least one number')
  }
  if (password && !passwordValidation.value.hasSpecial) {
    passwordValidationErrors.value.push('Password must include at least one special character')
  }
}

function validatePasswordConfirmation() {
  const password = passwordForm.password
  const confirmation = passwordForm.password_confirmation
  
  passwordValidation.value.passwordsMatch = password === confirmation && password.length > 0
  
  // Update confirmation errors
  passwordConfirmationErrors.value = []
  if (confirmation && !passwordValidation.value.passwordsMatch) {
    passwordConfirmationErrors.value.push('Passwords do not match')
  }
}

// Methods
function updateProfile() {
  if (!user.value?.id) {
    console.error('User not loaded')
    return
  }
  
  profileForm.patch(route('profile.update'), {
    onSuccess: () => {
      console.log('Profile updated successfully')
    },
    onError: (errors) => {
      console.error('Profile update errors:', errors)
    }
  })
}

function updatePassword() {
  if (!user.value?.id) {
    console.error('User not loaded')
    return
  }

  // Clear previous messages
  passwordSuccess.value = false
  passwordError.value = ''
  passwordForm.clearErrors()

  // Validate before submitting
  if (!isPasswordValid.value) {
    passwordError.value = 'Please ensure all password requirements are met'
    return
  }
  
  passwordForm.patch(route('profile.password.update'), {
    onSuccess: () => {
      console.log('Password updated successfully')
      passwordSuccess.value = true
      resetPasswordForm()
      
      // Auto-hide success message after 5 seconds
      setTimeout(() => {
        passwordSuccess.value = false
      }, 5000)
    },
    onError: (errors) => {
      console.error('Password update errors:', errors)
      
      // Show specific error messages
      if (errors.current_password) {
        passwordError.value = 'Current password is incorrect'
      } else if (errors.password) {
        passwordError.value = errors.password[0] || 'Password validation failed'
      } else {
        passwordError.value = 'Failed to update password. Please try again.'
      }
    }
  })
}

function resetProfileForm() {
  profileForm.reset()
  profileForm.clearErrors()
  
  // Reset to current user values
  if (user.value) {
    profileForm.username = user.value.username || ''
    profileForm.email = user.value.email || ''
    profileForm.first_name = user.value.first_name || ''
    profileForm.last_name = user.value.last_name || ''
  }
}

function resetPasswordForm() {
  passwordForm.reset()
  passwordForm.clearErrors()
  passwordValidationErrors.value = []
  passwordConfirmationErrors.value = []
  
  // Reset validation state
  passwordValidation.value = {
    minLength: false,
    hasUppercase: false,
    hasLowercase: false,
    hasNumber: false,
    hasSpecial: false,
    passwordsMatch: false
  }
}

function logout() {
  router.post(route('logout'))
}

function formatDate(dateString) {
  if (!dateString) return 'Unknown'
  
  try {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    })
  } catch (error) {
    return 'Invalid Date'
  }
}
</script>

<style scoped>
.custom-title {
  font-size: 2.5rem;
  font-weight: bold;
}

.v-card {
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.v-card-title {
  border-bottom: 1px solid #e0e0e0;
}

.text-success {
  color: rgb(var(--v-theme-success)) !important;
}

.text-error {
  color: rgb(var(--v-theme-error)) !important;
}
</style>