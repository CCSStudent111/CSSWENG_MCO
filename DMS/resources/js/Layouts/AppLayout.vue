<template>
    <v-app id="inspire">
        <v-app-bar class="elevation-1">
            
                <v-btn icon>
                    <v-icon size="32">mdi-account-circle</v-icon>
                </v-btn>

                <div class="d-flex flex-column">
                    <span class="text-subtitle-2">Franz</span>
                    <span class="text-caption text-grey">Admin</span>
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

</script>


<style scoped>

</style>
