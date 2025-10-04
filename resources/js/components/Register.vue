<template>
  <div class="login-wrapper">
    <div class="login-container">
      <!-- Logo/Brand -->
      <div class="login-header">
        <div class="brand-icon">🛒</div>
        <h1 class="brand-title">E-Commerce</h1>
        <p class="brand-subtitle">Create your account</p>
      </div>

      <!-- Register Form -->
      <form @submit.prevent="handleRegister" class="login-form">
        <!-- Name Input -->
        <div class="form-group">
          <label for="name" class="form-label">Full Name</label>
          <input 
            type="text" 
            id="name"
            v-model="form.name"
            class="form-input"
            :class="{ 'input-error': errors.name }"
            placeholder="Enter your name"
            required
          >
          <span v-if="errors.name" class="error-message">{{ errors.name }}</span>
        </div>

        <!-- Email Input -->
        <div class="form-group">
          <label for="email" class="form-label">Email Address</label>
          <input 
            type="email" 
            id="email"
            v-model="form.email"
            class="form-input"
            :class="{ 'input-error': errors.email }"
            placeholder="Enter your email"
            required
          >
          <span v-if="errors.email" class="error-message">{{ errors.email }}</span>
        </div>

        <!-- Password Input -->
        <div class="form-group">
          <label for="password" class="form-label">Password</label>
          <div class="password-wrapper">
            <input 
              :type="showPassword ? 'text' : 'password'"
              id="password"
              v-model="form.password"
              class="form-input"
              :class="{ 'input-error': errors.password }"
              placeholder="Enter your password"
              required
            >
            <button 
              type="button" 
              class="password-toggle"
              @click="showPassword = !showPassword"
            >
              {{ showPassword ? '👁️' : '👁️‍🗨️' }}
            </button>
          </div>
          <span v-if="errors.password" class="error-message">{{ errors.password }}</span>
        </div>

        <!-- Confirm Password Input -->
        <div class="form-group">
          <label for="password_confirmation" class="form-label">Confirm Password</label>
          <div class="password-wrapper">
            <input 
              :type="showConfirmPassword ? 'text' : 'password'"
              id="password_confirmation"
              v-model="form.password_confirmation"
              class="form-input"
              :class="{ 'input-error': errors.password_confirmation }"
              placeholder="Confirm your password"
              required
            >
            <button 
              type="button" 
              class="password-toggle"
              @click="showConfirmPassword = !showConfirmPassword"
            >
              {{ showConfirmPassword ? '👁️' : '👁️‍🗨️' }}
            </button>
          </div>
          <span v-if="errors.password_confirmation" class="error-message">{{ errors.password_confirmation }}</span>
        </div>

        <!-- Error Alert -->
        <div v-if="registerError" class="alert alert-error">
          {{ registerError }}
        </div>

        <!-- Submit Button -->
        <button 
          type="submit" 
          class="btn-login"
          :class="{ 'btn-loading': isLoading }"
          :disabled="isLoading"
        >
          <span v-if="isLoading" class="spinner"></span>
          <span v-else>Create Account</span>
        </button>
      </form>

      <!-- Footer Links -->
      <div class="login-footer">
        <p class="footer-text">
          Already have an account? 
          <a href="/login" class="footer-link">Sign in</a>
        </p>
      </div>
    </div>

    <!-- Decorative Background -->
    <div class="background-decoration">
      <div class="decoration-circle circle-1"></div>
      <div class="decoration-circle circle-2"></div>
      <div class="decoration-circle circle-3"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Register",
  data() {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      errors: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      registerError: '',
      isLoading: false,
      showPassword: false,
      showConfirmPassword: false
    }
  },
  methods: {
    validateForm() {
      this.errors = { name: '', email: '', password: '', password_confirmation: '' };
      let isValid = true;

      // Name validation
      if (!this.form.name || this.form.name.trim().length < 2) {
        this.errors.name = 'Name must be at least 2 characters';
        isValid = false;
      }

      // Email validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!this.form.email) {
        this.errors.email = 'Email is required';
        isValid = false;
      } else if (!emailRegex.test(this.form.email)) {
        this.errors.email = 'Please enter a valid email';
        isValid = false;
      }

      // Password validation
      if (!this.form.password) {
        this.errors.password = 'Password is required';
        isValid = false;
      } else if (this.form.password.length < 6) {
        this.errors.password = 'Password must be at least 6 characters';
        isValid = false;
      }

      // Confirm password validation
      if (!this.form.password_confirmation) {
        this.errors.password_confirmation = 'Please confirm your password';
        isValid = false;
      } else if (this.form.password !== this.form.password_confirmation) {
        this.errors.password_confirmation = 'Passwords do not match';
        isValid = false;
      }

      return isValid;
    },

    async handleRegister() {
      // Clear previous errors
      this.registerError = '';

      // Validate form
      if (!this.validateForm()) {
        return;
      }

      this.isLoading = true;

      try {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
          this.registerError = 'Security token missing. Please refresh the page.';
          this.isLoading = false;
          return;
        }

        // Send register request
        const response = await fetch('/register', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            name: this.form.name,
            email: this.form.email,
            password: this.form.password,
            password_confirmation: this.form.password_confirmation
          })
        });

        const data = await response.json();

        if (response.ok) {
          // Success - redirect to catalogue
          window.location.href = data.redirect || '/catalogue';
        } else {
          // Show error message
          if (data.errors) {
            // Laravel validation errors
            this.errors = { ...this.errors, ...data.errors };
            this.registerError = 'Please fix the errors above';
          } else {
            this.registerError = data.message || 'Registration failed. Please try again.';
          }
        }
      } catch (error) {
        console.error('Registration error:', error);
        this.registerError = 'An error occurred. Please try again.';
      } finally {
        this.isLoading = false;
      }
    }
  }
}
</script>

<style scoped>
/* Main Wrapper */
.login-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 20px;
  position: relative;
  overflow: hidden;
}

/* Login Container */
.login-container {
  background: white;
  border-radius: 24px;
  padding: 48px;
  max-width: 440px;
  width: 100%;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  position: relative;
  z-index: 1;
}

/* Header */
.login-header {
  text-align: center;
  margin-bottom: 40px;
}

.brand-icon {
  font-size: 64px;
  margin-bottom: 16px;
}

.brand-title {
  font-size: 2rem;
  font-weight: 700;
  color: #212529;
  margin-bottom: 8px;
}

.brand-subtitle {
  color: #6c757d;
  font-size: 1rem;
  margin: 0;
}

/* Form */
.login-form {
  margin-bottom: 24px;
}

.form-group {
  margin-bottom: 20px;
}

.form-label {
  display: block;
  font-weight: 600;
  color: #495057;
  margin-bottom: 8px;
  font-size: 0.875rem;
}

.form-input {
  width: 100%;
  padding: 14px 16px;
  border: 2px solid #e9ecef;
  border-radius: 10px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: #f8f9fa;
}

.form-input:focus {
  outline: none;
  border-color: #667eea;
  background: white;
  box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.form-input.input-error {
  border-color: #dc3545;
}

.error-message {
  display: block;
  color: #dc3545;
  font-size: 0.875rem;
  margin-top: 6px;
}

/* Password Toggle */
.password-wrapper {
  position: relative;
}

.password-toggle {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  font-size: 1.25rem;
  cursor: pointer;
  padding: 4px;
  opacity: 0.6;
  transition: opacity 0.2s;
}

.password-toggle:hover {
  opacity: 1;
}

/* Alert */
.alert {
  padding: 12px 16px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-size: 0.875rem;
}

.alert-error {
  background: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

/* Submit Button */
.btn-login {
  width: 100%;
  padding: 16px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
}

.btn-login:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

.btn-login:active:not(:disabled) {
  transform: translateY(0);
}

.btn-login:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.btn-login.btn-loading {
  color: transparent;
}

/* Loading Spinner */
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

/* Footer */
.login-footer {
  text-align: center;
}

.footer-text {
  color: #6c757d;
  font-size: 0.875rem;
  margin: 0;
}

.footer-link {
  color: #667eea;
  text-decoration: none;
  font-weight: 600;
}

.footer-link:hover {
  text-decoration: underline;
}

/* Background Decoration */
.background-decoration {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  overflow: hidden;
  z-index: 0;
}

.decoration-circle {
  position: absolute;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.1);
  animation: float 20s infinite ease-in-out;
}

.circle-1 {
  width: 300px;
  height: 300px;
  top: -100px;
  left: -100px;
  animation-delay: 0s;
}

.circle-2 {
  width: 200px;
  height: 200px;
  bottom: -50px;
  right: -50px;
  animation-delay: 5s;
}

.circle-3 {
  width: 150px;
  height: 150px;
  top: 50%;
  right: 10%;
  animation-delay: 10s;
}

@keyframes float {
  0%, 100% { transform: translateY(0) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(180deg); }
}

/* Responsive */
@media (max-width: 576px) {
  .login-container {
    padding: 32px 24px;
  }

  .brand-icon {
    font-size: 48px;
  }

  .brand-title {
    font-size: 1.5rem;
  }

  .form-group {
    margin-bottom: 16px;
  }
}
</style>