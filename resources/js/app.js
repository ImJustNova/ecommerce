import { createApp, reactive } from 'vue'
import Navbar from './components/Navbar.vue'
import Catalogue from './components/Catalogue.vue'

// Create a shared reactive state for cart
const cartState = reactive({
    count: parseInt(document.getElementById('initial-cart-count')?.value || 0)
})

// Mount navbar with shared cart state
const navbarApp = createApp({
    data() {
        return {
            cartState
        }
    }
})
navbarApp.component('navbar-component', Navbar)
navbarApp.mount('#app')

// Mount catalogue with shared cart state
const catalogueElement = document.getElementById('catalogue-app')
if (catalogueElement) {
    const catalogueApp = createApp({
        data() {
            return {
                cartState
            }
        }
    })
    catalogueApp.component('catalogue-component', Catalogue)
    catalogueApp.mount('#catalogue-app')
}

// Make cartState globally available for incrementing
window.cartState = cartState