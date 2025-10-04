import { createApp, reactive } from 'vue'
import Navbar from './components/Navbar.vue'
import Catalogue from './components/Catalogue.vue'
import Login from './components/Login.vue'

const cartState = reactive({
    count: parseInt(document.getElementById('initial-cart-count')?.value || 0)
})

//navbar(header)
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

//catalogue
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

//login
const loginElement = document.getElementById('login-app')
if (loginElement) {
    const loginApp = createApp({})
    loginApp.component('login-component', Login)
    loginApp.mount('#login-app')
}

// Make cartState globally available
window.cartState = cartState