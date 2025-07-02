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
    body{
    background-color: #fff;
    font-family: "Poppins", sans-serif !important;
    }
    a{
        text-decoration: none !important;
        color: inherit;
    }
    input{
        border: solid 1px rgba(102,102,102,.35);
        border-radius: 12px;
        font-family: "Poppins", sans-serif;
        font-weight: 400;
        font-style: normal;
        color: #333;
        outline: none;
    }

    /* sign in, forgot password, etc */
    .center-height{
        height: 100vh;
    }
    .in-card{
        padding: 40px 35px 40px 35px;
        border: solid 1px rgba(102,102,102,.50);
        border-radius: 24px;
    }
    .in-title{
        font-weight: 500;
        font-style: normal;
        font-size: 32px;
        text-align: center;
        color: #333;
    }
    .in-fieldname{
        font-weight: 400;
        font-style: normal;
        font-size: 16px;
        color: #666;
    }
    .in-input{
        margin-top: 7px;
        padding: 15px 11px;
        font-size: 16px;
    }
    .in-button{
        padding: 15px 0px;
        border-radius: 40px;
        background-color: #111;
        font-weight: 400;
        font-style: normal;
        font-size: 22px;
        color: #fff;
        text-align: center;
    }
</style>