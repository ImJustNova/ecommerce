import { createApp, reactive } from 'vue'
import Navbar from './components/Navbar.vue'
import Catalogue from './components/Catalogue.vue'
import Login from './components/Login.vue'
import Register from './components/Register.vue'

// Create a shared reactive state for cart
const cartState = reactive({
    count: parseInt(document.getElementById('initial-cart-count')?.value || 0)
})

// Mount navbar (if exists)
const navbarElement = document.getElementById('app')
if (navbarElement) {
    const navbarApp = createApp({
        data() {
            return { cartState }
        }
    })
    navbarApp.component('navbar-component', Navbar)
    navbarApp.mount('#app')
}

// Mount catalogue (if exists)
const catalogueElement = document.getElementById('catalogue-app')
if (catalogueElement) {
    const catalogueApp = createApp({
        data() {
            return { cartState }
        }
    })
    catalogueApp.component('catalogue-component', Catalogue)
    catalogueApp.mount('#catalogue-app')
}

// Mount login (if exists)
const loginElement = document.getElementById('login-app')
if (loginElement) {
    const loginApp = createApp({})
    loginApp.component('login-component', Login)
    loginApp.mount('#login-app')
}

// Mount register (if exists)
const registerElement = document.getElementById('register-app')
if (registerElement) {
    const registerApp = createApp({})
    registerApp.component('register-component', Register)
    registerApp.mount('#register-app')
}

// Make cartState globally available
window.cartState = cartState