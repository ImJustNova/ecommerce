import { createApp } from 'vue'
import Navbar from './components/Navbar.vue'

createApp(Navbar, {
    cartCount: 0,
    showSearch: true
}).mount('#navbar')
