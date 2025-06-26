<template>
  <v-app id="inspire">

    <v-app-bar>
      <v-app-bar-title>Application</v-app-bar-title>
    </v-app-bar>

    <v-main>
      <v-container>
        <v-row>
          <v-col cols="8">
            <v-card class="border pa-2" dark>
              <v-row class="pa-1">
                <v-card-title>Management</v-card-title>
              </v-row>
              <v-row>

              </v-row>
              <v-row class="pa-3">
                <v-col v-for="n in 3" :key="n" cols="4">
                  <v-card class="pa-2 border" dark>
                    <v-card-title>This is a card</v-card-title>
                    <v-card-text>
                      Content inside the card.
                    </v-card-text>
                  </v-card>
                </v-col>
              </v-row>

              <v-row class="justify-end">
                <v-col cols="auto" class="pa-2">
                  <v-btn color="black" dark>
                    Click Me
                  </v-btn>
                </v-col>
              </v-row>
            </v-card>
          </v-col>
          <v-col cols="4">
            <v-card class="border">

            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup>
import { ref } from 'vue'

const drawer = ref(null)
</script>

<script>
export default {
  data: () => ({ drawer: null }),
}
</script>