<template>
    <AppLayout>
        <div class="custom-title mb-4">Edit Hospital</div>
    
        <v-form @submit.prevent="submit">
        <v-text-field v-model="form.name" label="Hospital Name" required></v-text-field>
        <v-text-field v-model="form.branch" label="Branch" required></v-text-field>
    
        <v-btn type="submit" color="primary">Edit Hospital</v-btn>
        </v-form>
    </AppLayout>
</template>

<script>

</script>

