<?php // login.php - Vuetify Login Page ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Sign In</title>
    <!-- Vuetify CSS -->
    <link href="https://cdn.jsdelivr.net/npm/vuetify@3.6.6/dist/vuetify.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Vue.js -->
    <script src="https://cdn.jsdelivr.net/npm/vue@3.4.21/dist/vue.global.prod.js"></script>
    <!-- Vuetify JS -->
    <script src="https://cdn.jsdelivr.net/npm/vuetify@3.6.6/dist/vuetify.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <div id="app">
      <v-app>
        <v-main>
          <v-container class="center-height d-flex justify-center align-center">
            <v-card class="in-card pa-8" width="400">
              <v-card-title class="in-title text-h5 mb-4">Sign In</v-card-title>
              <v-form action="home.php" method="post">
                <v-text-field hide-details
                  label="Username"
                  name="username"
                  variant="outlined"
                  class="mb-4"
                  required
                ></v-text-field>
                <v-text-field hide-details
                  label="Password"
                  name="password"
                  type="password"
                  variant="outlined"
                  class="mb-4"
                  required
                ></v-text-field>
                <v-btn type="submit" color="black" size="x-large" block class="in-button mt-3 text-h6">Log In</v-btn>
              </v-form>
            </v-card>
          </v-container>
        </v-main>
      </v-app>
    </div>
    <script>
      const { createApp } = Vue;
      const vuetify = Vuetify.createVuetify();
      createApp({}).use(vuetify).mount('#app');
    </script>
  </body>
</html>