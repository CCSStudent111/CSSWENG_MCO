<template>
    <div class="container-fluid d-flex justify-content-center align-items-center center-height">
        <div class="d-flex flex-column in-card col-4">
            <div class="in-title">Sign In</div>
            <form action="home.html" class="d-flex flex-column mt-5 row-gap-3">
                <div class="d-flex flex-column">
                    <label for="username" class="in-fieldname">Username</label>
                    <input type="text" class="in-input" id="username">
                </div>
                <div class="d-flex flex-column">
                    <div class="in-fieldname">Password</div>
                    <input type="password" class="in-input">
                </div>
                <button type="submit" class="in-button mt-3">Log In</button>
            </form>
        </div>
    </div>
</template>

<script setup>

</script>

<style scoped>
    
</style>
