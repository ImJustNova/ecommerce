import { createApp } from 'vue'
import Navbar from './components/Navbar.vue'
import Catalogue from './components/Catalogue.vue'

// Mount navbar
const navbarApp = createApp({})
navbarApp.component('navbar-component', Navbar)
navbarApp.mount('#app')

// Mount catalogue (only if the element exists on the page)
const catalogueElement = document.getElementById('catalogue-app')
if (catalogueElement) {
    const catalogueApp = createApp({})
    catalogueApp.component('catalogue-component', Catalogue)
    catalogueApp.mount('#catalogue-app')
}