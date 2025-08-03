<template>
  <v-app>
    <!-- Top Header with Logo and Title -->
    <v-app-bar
      color="primary"
      dark
      elevation="2"
      height="80"
      app
      class="top-header"
    >
      <div class="d-flex align-center fill-height px-4 w-100">
        <!-- Logo Space -->
        <Link href="/" style="text-decoration: none; color: inherit;">
          <v-tooltip text="Dashboard" location="bottom">
            <template #activator="{ props }">
              <div v-bind="props" class="logo-container mr-4">
                <v-avatar size="48">
                  <v-img 
                    src="/logo.png" 
                    alt="Company Logo"
                    @error="console.log('Image failed to load')"
                    @load="console.log('Image loaded successfully')"
                  >
                    <!-- Fallback content -->
                    <template v-slot:placeholder>
                      <v-icon size="32" color="primary">mdi-file-document-multiple</v-icon>
                    </template>
                  </v-img>
                </v-avatar>
              </div>
            </template>
          </v-tooltip>
        </Link>

        <!-- Title -->
        <div class="title-section">
          <h1 class="app-title">Document Management System</h1>
          <p class="app-subtitle mb-0">Streamlined Document Solutions</p>
        </div>
        
        <v-spacer></v-spacer>
        
        <!-- User Menu -->
        <v-menu>
          <template v-slot:activator="{ props }">
            <v-btn
              v-bind="props"
              variant="text"
              class="d-flex align-center user-menu-btn"
            >
              <v-avatar size="32" class="mr-2" color="white">
                <v-icon color="primary">mdi-account-circle</v-icon>
              </v-avatar>
              <div class="d-none d-sm-block text-left mr-2">
                <div class="user-name">{{ user?.username || 'User' }}</div>
                <div class="user-role">{{ getUserRole() }}</div>
              </div>
              <v-icon>mdi-chevron-down</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item>
              <Link href="/profile" style="text-decoration: none; color: inherit;">
                <v-list-item-title>
                  <v-icon class="mr-2">mdi-account</v-icon>
                  Profile
                </v-list-item-title>
              </Link>
            </v-list-item>
            <v-divider></v-divider>
            <v-list-item>
              <Link href="/logout" method="post" as="button" style="text-decoration: none; color: inherit;">
                <v-list-item-title>
                  <v-icon class="mr-2">mdi-logout</v-icon>
                  Logout
                </v-list-item-title>
              </Link>
            </v-list-item>
          </v-list>
        </v-menu>
      </div>
    </v-app-bar>

    <!-- Side Navigation -->
    <v-navigation-drawer
      app
      permanent
      color="primary"
      dark
      width="80"
      class="pa-2 side-nav"
    >
      <div class="d-flex flex-column fill-height">
        <!-- Main navigation items -->
        <v-list nav density="compact" class="d-flex flex-column align-center">
          <!-- FIXED: Correct the style syntax -->
          <Link href="/documents" style="text-decoration: none; color: inherit;">
            <v-tooltip text="Documents" location="right">
              <template #activator="{ props }">
                <v-list-item 
                  v-bind="props" 
                  class="ma-1 d-flex justify-center align-center nav-item"
                  style="min-height: 56px; width: 56px;"
                >
                  <v-icon size="28">mdi-file-document-outline</v-icon>
                </v-list-item>
              </template>
            </v-tooltip>
          </Link>
          
          <!-- Manager/Admin features - add debug info -->
          <Link v-if="isAdminOrManager()" href="/documents/pending" style="text-decoration: none; color: inherit;">
            <v-tooltip text="Approve Pending Documents" location="right">
              <template #activator="{ props }">
                <v-list-item 
                  v-bind="props" 
                  class="ma-1 d-flex justify-center align-center nav-item"
                  style="min-height: 56px; width: 56px;"
                >
                  <v-icon size="28">mdi-file-document-check-outline</v-icon>
                </v-list-item>
              </template>
            </v-tooltip>
          </Link>
          
          <Link v-if="isAdmin()" href="/document-types" style="text-decoration: none; color: inherit;">
            <v-tooltip text="Manage Document Types" location="right">
              <template #activator="{ props }">
                <v-list-item 
                  v-bind="props" 
                  class="ma-1 d-flex justify-center align-center nav-item"
                  style="min-height: 56px; width: 56px;"
                >
                  <v-icon size="28">mdi-file-document-edit-outline</v-icon>
                </v-list-item>
              </template>
            </v-tooltip>
          </Link>
          
          <Link href="/clients" style="text-decoration: none; color: inherit;">
            <v-tooltip text="Manage Clients" location="right">
              <template #activator="{ props }">
                <v-list-item 
                  v-bind="props" 
                  class="ma-1 d-flex justify-center align-center nav-item"
                  style="min-height: 56px; width: 56px;"
                >
                  <v-icon size="28">mdi-account-box-multiple-outline</v-icon>
                </v-list-item>
              </template>
            </v-tooltip>
          </Link>
          
          <Link v-if="isAdmin()" href="/departments" style="text-decoration: none; color: inherit;">
            <v-tooltip text="Manage Departments" location="right">
              <template #activator="{ props }">
                <v-list-item 
                  v-bind="props" 
                  class="ma-1 d-flex justify-center align-center nav-item"
                  style="min-height: 56px; width: 56px;"
                >
                  <v-icon size="28">mdi-domain</v-icon>
                </v-list-item>
              </template>
            </v-tooltip>
          </Link>
          
          <Link v-if="isAdmin()" href="/users" style="text-decoration: none; color: inherit;">
            <v-tooltip text="Manage Users" location="right">
              <template #activator="{ props }">
                <v-list-item 
                  v-bind="props" 
                  class="ma-1 d-flex justify-center align-center nav-item"
                  style="min-height: 56px; width: 56px;"
                >
                  <v-icon size="28">mdi-account-group</v-icon>
                </v-list-item>
              </template>
            </v-tooltip>
          </Link>
          
          <Link v-if="isAdmin()" href="/department-document-types" style="text-decoration: none; color: inherit;">
            <v-tooltip text="Department Document Types" location="right">
              <template #activator="{ props }">
                <v-list-item 
                  v-bind="props" 
                  class="ma-1 d-flex justify-center align-center nav-item"
                  style="min-height: 56px; width: 56px;"
                >
                  <v-icon size="28">mdi-file-tree</v-icon>
                </v-list-item>
              </template>
            </v-tooltip>
          </Link>
        </v-list>
        
        <!-- Spacer to push logout to bottom -->
        <v-spacer></v-spacer>
        
        <!-- Logout button at bottom -->
        <div class="d-flex flex-column align-center pb-2">
          <v-divider class="my-3" style="width: 80%;"></v-divider>
          <Link href="/logout" method="post" as="button" style="text-decoration: none; color: inherit;">
            <v-tooltip text="Logout" location="right">
              <template #activator="{ props }">
                <v-list-item 
                  v-bind="props" 
                  class="ma-1 d-flex justify-center align-center nav-item"
                  style="min-height: 56px; width: 56px;"
                >
                  <v-icon size="28">mdi-logout</v-icon>
                </v-list-item>
              </template>
            </v-tooltip>
          </Link>
        </div>
      </div>
    </v-navigation-drawer>

    <v-main>
      <div class="app-content">
        <slot />
      </div>
    </v-main>
  </v-app>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

// Get user data from Inertia props
const { props } = usePage()
const user = props.auth?.user

const imageLoaded = ref(false)

// FIXED: Helper function to get user role
function getUserRole() {
  console.log('getUserRole called - checking user:', user)
  
  if (user?.is_admin) return 'Administrator'
  if (user?.role === 'Manager') return 'Manager' 
  return 'Employee'
}

// FIXED: Combine admin and manager checks
function isAdminOrManager() {
  const result = user?.is_admin || user?.role === 'Manager'  
  console.log('isAdminOrManager called - result:', result)
  return result
}

// Admin check
function isAdmin() {
  return user?.is_admin
}

// Manager check
function isManager() {
  return user?.role === 'Manager'  // FIXED: Use role field
}
</script>

<style scoped>
.app-content {
  padding: 32px 24px 24px 24px;
}

/* Top Header Styles */
.top-header {
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.logo-container {
  display: flex;
  align-items: center;
}

.title-section {
  color: white;
}

.app-title {
  font-size: 1.75rem;
  font-weight: 600;
  line-height: 1.2;
  margin: 0;
  color: white;
}

.app-subtitle {
  font-size: 0.875rem;
  opacity: 0.9;
  font-weight: 400;
  color: white;
}

/* User Menu Styles */
.user-menu-btn {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 8px;
}

.user-name {
  font-size: 0.875rem;
  font-weight: 500;
  line-height: 1.2;
}

.user-role {
  font-size: 0.75rem;
  opacity: 0.8;
  line-height: 1;
}

/* Side Navigation Styles */
.side-nav {
  border-right: 1px solid rgba(255, 255, 255, 0.1);
}

.nav-item {
  border-radius: 8px;
  transition: all 0.2s ease;
}

.nav-item:hover {
  background-color: rgba(255, 255, 255, 0.1);
  transform: scale(1.05);
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .app-title {
    font-size: 1.5rem;
  }
  
  .app-subtitle {
    display: none;
  }
  
  .logo-container {
    margin-right: 12px;
  }
}

@media (max-width: 600px) {
  .title-section {
    display: none;
  }
  
  .logo-container {
    margin-right: 0;
  }

  .user-menu-fixed {
  position: fixed;
  top: 16px;
  right: 16px;
  z-index: 1000;
}

.user-menu-btn {
  background: rgba(25, 118, 210, 0.9);
  border-radius: 8px;
  backdrop-filter: blur(10px);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.user-name {
  font-size: 0.875rem;
  font-weight: 500;
  line-height: 1.2;
  color: white;
}

.user-role {
  font-size: 0.75rem;
  opacity: 0.8;
  line-height: 1;
  color: white;
}
}
</style>

