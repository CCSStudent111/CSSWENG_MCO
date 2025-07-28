<template>
  <v-app>
    <v-app-bar
      color="white"
      elevation="1"
      height="64"
      app
    >
      <v-spacer></v-spacer>
      <v-menu>
        <template v-slot:activator="{ props }">
          <v-btn
            v-bind="props"
            variant="text"
            class="d-flex align-center"
          >
            <v-avatar size="32" class="mr-2">
              <v-icon>mdi-account-circle</v-icon>
            </v-avatar>
            <span class="d-none d-sm-inline">{{ user?.username || 'User' }}</span>
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
    </v-app-bar>

    <v-navigation-drawer
      app
      permanent
      color="primary"
      dark
      width="80"
      class="pa-2"
    >
      <v-list nav density="compact" class="d-flex flex-column align-center">
        <Link href="/" style="text-decoration: none; color: inherit;">
          <v-tooltip text="Dashboard" location="right">
            <template #activator="{ props }">
              <v-list-item 
                v-bind="props" 
                class="ma-1 d-flex justify-center align-center"
                style="min-height: 56px; width: 56px;"
              >
                <v-icon size="28">mdi-view-dashboard</v-icon>
              </v-list-item>
            </template>
          </v-tooltip>
        </Link>
        <Link href="/documents" style="text-decoration: none; color: inherit;">
          <v-tooltip text="Documents" location="right">
            <template #activator="{ props }">
              <v-list-item 
                v-bind="props" 
                class="ma-1 d-flex justify-center align-center"
                style="min-height: 56px; width: 56px;"
              >
                <v-icon size="28">mdi-file-document</v-icon>
              </v-list-item>
            </template>
          </v-tooltip>
        </Link>
        <Link href="/clients" style="text-decoration: none; color: inherit;">
          <v-tooltip text="Clients" location="right">
            <template #activator="{ props }">
              <v-list-item 
                v-bind="props" 
                class="ma-1 d-flex justify-center align-center"
                style="min-height: 56px; width: 56px;"
              >
                <v-icon size="28">mdi-account-group</v-icon>
              </v-list-item>
            </template>
          </v-tooltip>
        </Link>
        <!-- Only show Users link for admin users -->
        <Link v-if="user?.is_admin" href="/users" style="text-decoration: none; color: inherit;">
          <v-tooltip text="Users" location="right">
            <template #activator="{ props }">
              <v-list-item 
                v-bind="props" 
                class="ma-1 d-flex justify-center align-center"
                style="min-height: 56px; width: 56px;"
              >
                <v-icon size="28">mdi-account</v-icon>
              </v-list-item>
            </template>
          </v-tooltip>
        </Link>
        <v-divider class="my-3" style="width: 80%;"></v-divider>
        <Link href="/logout" method="post" as="button" style="text-decoration: none; color: inherit;">
          <v-tooltip text="Logout" location="right">
            <template #activator="{ props }">
              <v-list-item 
                v-bind="props" 
                class="ma-1 d-flex justify-center align-center"
                style="min-height: 56px; width: 56px;"
              >
                <v-icon size="28">mdi-logout</v-icon>
              </v-list-item>
            </template>
          </v-tooltip>
        </Link>
      </v-list>
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

// Get user data from Inertia props
const { props } = usePage()
const user = props.auth?.user
</script>

<style scoped>
.app-content {
  padding: 32px 24px 24px 24px;
}

/* Custom styles for navigation items */
.v-list-item {
  border-radius: 8px;
  transition: all 0.2s ease;
}

.v-list-item:hover {
  background-color: rgba(255, 255, 255, 0.1);
  transform: scale(1.05);
}
</style>

