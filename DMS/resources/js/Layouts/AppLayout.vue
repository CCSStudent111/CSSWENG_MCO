<template>
    <v-app id="inspire">
        <v-app-bar class="elevation-1">

            <Link :href="route('profile.index')" class="d-flex align-center">
            <v-btn icon>
                <v-icon size="32">mdi-account-circle</v-icon>
            </v-btn>
            </Link>

            <div class="d-flex flex-column">
                <span class="text-subtitle-2">{{ user?.username }}</span>
                <span class="text-caption text-grey">
                    {{ user?.department?.name }}
                    <template v-if="user?.role === 'Manager'"> | Manager</template>
                    <template v-if="user?.is_admin"> | Admin</template>
                </span>
            </div>
        </v-app-bar>

        <v-main>
            <v-container>
                <slot />
            </v-container>
        </v-main>
    </v-app>
</template>

<script setup>
import { usePage, Link } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;
</script>


<style scoped></style>
