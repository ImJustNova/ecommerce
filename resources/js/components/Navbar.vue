<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="/catalogue">E-Commerce</a>

      <div class="d-flex align-items-center ms-auto">
        <form v-if="showSearch" @submit.prevent="searchProducts" class="d-flex me-3">
          <input 
            type="text" 
            v-model="searchQuery" 
            class="form-control form-control-sm me-2" 
            placeholder="Search products..."
          >
          <button type="submit" class="btn btn-sm btn-primary">Search</button>
        </form>

        <a href="/catalogue" class="btn btn-outline-secondary text-dark me-2">Catalogue</a>
        <a href="/orders" class="btn btn-outline-secondary text-dark me-2">Orders</a>
        <a href="/cart" class="btn btn-outline-primary position-relative me-2">
          🛒
          <span 
            v-if="cartCount > 0" 
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
          >
            {{ cartCount }}
          </span>
        </a>
        
        <form method="POST" action="/logout" style="display: inline;">
          <input type="hidden" name="_token" :value="csrfToken">
          <button type="submit" class="btn btn-outline-danger">Logout</button>
        </form>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: "NavbarComponent",
  props: {
    cartCount: {
      type: Number,
      default: 0
    },
    showSearch: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      searchQuery: this.getCurrentSearchQuery(),
      csrfToken: document.querySelector('meta[name="csrf-token"]')?.content || ''
    }
  },
  methods: {
    searchProducts() {
      window.location.href = `/?search=${encodeURIComponent(this.searchQuery)}`;
    },
    getCurrentSearchQuery() {
      const params = new URLSearchParams(window.location.search);
      return params.get("search") || "";
    }
  }
}
</script>