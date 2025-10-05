<template>
  <div>
    <div v-if="products.length === 0" class="empty-state">
      <div class="empty-icon">📦</div>
      <h3>No Products Available</h3>
      <p class="text-muted">Check back soon for new items!</p>
    </div>

    <div v-else class="row g-4">
      <div v-for="product in products" :key="product.id" class="col-lg-4 col-md-6">
        <div class="product-card">

          <div class="product-image">
            <div class="product-icon">{{ getProductIcon(product.name) }}</div>
          </div>

          <div class="product-body">
            <h5 class="product-title">{{ product.name }}</h5>
            
            <div class="product-price">
              <span class="price-currency">$</span>
              <span class="price-amount">{{ formatPrice(product.price) }}</span>
            </div>

            <div class="stock-info">
              <span 
                class="stock-badge" 
                :class="getStockClass(product.stock)"
              >
                <span class="stock-dot"></span>
                {{ getStockText(product.stock) }}
              </span>
            </div>

            <button 
              class="btn-add-cart"
              :class="{ 'btn-loading': loadingProducts[product.id] }"
              :disabled="loadingProducts[product.id] || product.stock === 0"
              @click="addToCart(product.id)"
            >
              <span v-if="loadingProducts[product.id]" class="spinner"></span>
              <span v-else>
                🛒 {{ product.stock === 0 ? 'Out of Stock' : 'Add to Cart' }}
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <transition name="toast">
      <div v-if="showToast" class="toast-notification" :class="toastType">
        {{ toastMessage }}
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: "Catalogue",
  props: {
    initialProducts: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      products: this.initialProducts,
      loadingProducts: {},
      showToast: false,
      toastMessage: '',
      toastType: 'success'
    }
  },
  methods: {
    getProductIcon(name) {
      const icons = {
        'laptop': '💻',
        'phone': '📱',
        'headphone': '🎧',
        'smartwatch': '⌚',
        'tablet': '📱',
        'keyboard': '⌨️',
        'mouse': '🖱️',
        'camera': '📷',
        'speaker': '🔊',
        'monitor': '🖥️'
      };
      
      const lowerName = name.toLowerCase();
      
      if (lowerName.includes('headphone')) return '🎧';
      if (lowerName.includes('laptop')) return '💻';
      if (lowerName.includes('tablet')) return '📱';
      if (lowerName.includes('phone')) return '📱';
      if (lowerName.includes('smartwatch') || lowerName.includes('watch')) return '⌚';
      if (lowerName.includes('keyboard')) return '⌨️';
      if (lowerName.includes('mouse')) return '🖱️';
      if (lowerName.includes('camera')) return '📷';
      if (lowerName.includes('speaker')) return '🔊';
      if (lowerName.includes('monitor')) return '🖥️';
      
      return '📦';
    },

    formatPrice(price) {
      return parseFloat(price).toFixed(2);
    },

    getStockClass(stock) {
      if (!stock || stock === 0) return 'stock-out';
      if (stock < 10) return 'stock-low';
      return 'stock-available';
    },

    getStockText(stock) {
      if (!stock || stock === 0) return 'Out of Stock';
      if (stock < 10) return `Only ${stock} left`;
      return `${stock} in stock`;
    },

    async addToCart(productId) {
      this.loadingProducts[productId] = true;

      try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
          console.error('CSRF token not found');
          this.showToastNotification('Security token missing. Please refresh the page.', 'error');
          this.loadingProducts[productId] = false;
          return;
        }

        const response = await fetch(`/cart/add/${productId}`, {
          method: "POST",
          headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
            "Accept": "application/json"
          }
        });

        if (response.ok) {
          this.showToastNotification('Product added to cart! 🎉', 'success');

          if (window.cartState) {
            window.cartState.count++;
          }
        } else {
          const errorData = await response.json().catch(() => ({}));
          this.showToastNotification(errorData.message || 'Failed to add product', 'error');
        }
      } catch (error) {
        console.error('Add to cart error:', error);
        this.showToastNotification('An error occurred. Please try again.', 'error');
      } finally {
        this.loadingProducts[productId] = false;
      }
    },

    showToastNotification(message, type = 'success') {
      this.toastMessage = message;
      this.toastType = type;
      this.showToast = true;

      setTimeout(() => {
        this.showToast = false;
      }, 3000);
    }
  }
}
</script>

<style scoped>
.empty-state {
  text-align: center;
  padding: 80px 20px;
  background: #f8f9fa;
  border-radius: 12px;
  margin: 20px 0;
}

.empty-icon {
  font-size: 80px;
  margin-bottom: 20px;
}

.empty-state h3 {
  color: #495057;
  margin-bottom: 10px;
}

.product-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  height: 100%;
  display: flex;
  flex-direction: column;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.product-image {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 200px;
}

.product-icon {
  font-size: 80px;
  filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
}

.product-body {
  padding: 24px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.product-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #212529;
  margin-bottom: 16px;
}

.product-price {
  display: flex;
  align-items: center;
  margin-bottom: 16px;
}

.price-currency {
  font-size: 2rem;
  font-weight: 700;
  color: #6c757d;
  margin-right: 4px;
}

.price-amount {
  font-size: 2rem;
  font-weight: 700;
  color: #212529;
}

.stock-info {
  margin-bottom: 20px;
}

.stock-badge {
  display: inline-flex;
  align-items: center;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 500;
}

.stock-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  margin-right: 6px;
  display: inline-block;
}

.stock-available {
  background: #d1f4e0;
  color: #0d6832;
}

.stock-available .stock-dot {
  background: #0d6832;
}

.stock-low {
  background: #fff3cd;
  color: #856404;
}

.stock-low .stock-dot {
  background: #ffc107;
}

.stock-out {
  background: #f8d7da;
  color: #721c24;
}

.stock-out .stock-dot {
  background: #dc3545;
}

.btn-add-cart {
  width: 100%;
  padding: 14px 24px;
  background: #0d6efd;
  color: white;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: auto;
}

.btn-add-cart:hover:not(:disabled) {
  background: #0b5ed7;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.btn-add-cart:active:not(:disabled) {
  transform: translateY(0);
}

.btn-add-cart:disabled {
  background: #6c757d;
  cursor: not-allowed;
  opacity: 0.6;
}

.btn-add-cart.btn-loading {
  position: relative;
  color: transparent;
}

.spinner {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  width: 20px;
  height: 20px;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: translate(-50%, -50%) rotate(360deg); }
}

.toast-notification {
  position: fixed;
  bottom: 30px;
  right: 30px;
  padding: 16px 24px;
  border-radius: 10px;
  font-weight: 500;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  z-index: 9999;
  max-width: 400px;
}

.toast-notification.success {
  background: #d1f4e0;
  color: #0d6832;
  border-left: 4px solid #0d6832;
}

.toast-notification.error {
  background: #f8d7da;
  color: #721c24;
  border-left: 4px solid #dc3545;
}

.toast-enter-active, .toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  transform: translateX(400px);
  opacity: 0;
}

.toast-leave-to {
  transform: translateY(30px);
  opacity: 0;
}

@media (max-width: 768px) {
  .product-image {
    min-height: 160px;
    padding: 30px;
  }

  .product-icon {
    font-size: 60px;
  }

  .product-body {
    padding: 20px;
  }

  .price-amount {
    font-size: 1.75rem;
  }

  .toast-notification {
    bottom: 20px;
    right: 20px;
    left: 20px;
    max-width: none;
  }
}
</style>