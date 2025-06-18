<script setup>

</script>

<template>
    <v-container fluid class="d-flex align-center justify-center screen-height">
        <v-card width="450" class="pa-7 light-border" flat>

            <div class="d-flex flex-column align-center">
                <div class="text-h6 mb-4">Sign In</div>
            </div>

            <div class="pa-2">
                <div class="text-caption text-grey-lighten-1">Username</div>
                <v-text-field density="compact" class="w-100 light-border" hide-details
                    variant="outlined"></v-text-field>
            </div>

            <div class="pa-2">
                <div class="text-caption text-grey-lighten-1">Password</div>
                <v-text-field density="compact" class="w-100 light-border" hide-details
                    variant="outlined"></v-text-field>
            </div>

            <div class="pa-2">
                <v-btn class="w-100 mt-1 rounded-button" color="grey lighten-1" size="large" flat>Log in</v-btn>
            </div>


            <div class="d-flex justify-end pa-2 mt-4">
                <inertia-link href="/forgot-password" class="text-caption text-decoration-underline cursor-pointer">
                    Forgot your password?
                </inertia-link>
            </div>

        </v-card>
    </v-container>
</template>

<style scoped>
.light-border {
    border: 2px solid #e0e0e0;
    border-radius: 12px;
}

.rounded-button {
    border-radius: 50px;
    text-transform: none;
    font-weight: 500;
}

.screen-height {
    height: 100vh;
}
</style>
