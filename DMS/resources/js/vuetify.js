import "vuetify/styles"
import { createVuetify } from "vuetify"
import * as components from "vuetify/components"
import * as directives from "vuetify/directives"
import { aliases, mdi } from 'vuetify/iconsets/mdi'
import "@mdi/font/css/materialdesignicons.css";
import { VFileUpload } from 'vuetify/labs/VFileUpload'

const myComponents = {
  ...components,
  VFileUpload,
}

export default createVuetify({
    components: myComponents,
    directives,

    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        },
    },
    theme: {
        defaultTheme: 'light',
        themes: {
            light: {
                colors: {
                    primary: '#2C2C2C',
                },
            },
            dark: {
                colors: {
                    primary: '#2C2C2C',
                },
            },
        },
    },
})