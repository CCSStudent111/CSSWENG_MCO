<template>
  <v-app>
    <v-navigation-drawer
      app
      permanent
      color="primary"
      dark
      width="80"
      class="pa-2"
    >
      <v-list nav dense>
        <Link href="/" style="text-decoration: none; color: inherit;">
          <v-tooltip text="Dashboard" location="right">
            <template #activator="{ props }">
              <v-list-item v-bind="props" class="justify-center">
                <v-list-item-icon>
                  <v-icon>mdi-view-dashboard</v-icon>
                </v-list-item-icon>
              </v-list-item>
            </template>
          </v-tooltip>
        </Link>
        <Link href="/documents" style="text-decoration: none; color: inherit;">
          <v-tooltip text="Documents" location="right">
            <template #activator="{ props }">
              <v-list-item v-bind="props" class="justify-center">
                <v-list-item-icon>
                  <v-icon>mdi-file-document</v-icon>
                </v-list-item-icon>
              </v-list-item>
            </template>
          </v-tooltip>
        </Link>
        <Link href="/clients" style="text-decoration: none; color: inherit;">
          <v-tooltip text="Clients" location="right">
            <template #activator="{ props }">
              <v-list-item v-bind="props" class="justify-center">
                <v-list-item-icon>
                  <v-icon>mdi-account-group</v-icon>
                </v-list-item-icon>
              </v-list-item>
            </template>
          </v-tooltip>
        </Link>
        <Link href="/users" style="text-decoration: none; color: inherit;">
          <v-tooltip text="Users" location="right">
            <template #activator="{ props }">
              <v-list-item v-bind="props" class="justify-center">
                <v-list-item-icon>
                  <v-icon>mdi-account</v-icon>
                </v-list-item-icon>
              </v-list-item>
            </template>
          </v-tooltip>
        </Link>
        <v-divider class="my-2"></v-divider>
        <Link href="/logout" method="post" as="button" style="text-decoration: none; color: inherit;">
          <v-tooltip text="Logout" location="right">
            <template #activator="{ props }">
              <v-list-item v-bind="props" class="justify-center">
                <v-list-item-icon>
                  <v-icon>mdi-logout</v-icon>
                </v-list-item-icon>
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
import { Link } from '@inertiajs/vue3'
</script>

<style scoped>
.app-content {
  padding: 32px 24px 24px 24px; /* top, right, bottom, left */
}
</style>
