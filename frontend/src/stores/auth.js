import { defineStore } from 'pinia'
import api from '../services/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    loading: false,
    error: null
  }),
  
  getters: {
    isAuthenticated: (state) => !!state.token,
    userName: (state) => state.user?.name || ''
  },
  
  actions: {
    async login(email, password) {
      this.loading = true
      this.error = null
      
      try {
        const response = await api.login({ email, password })
        this.user = response.data.user
        this.token = response.data.token
        return { success: true }
      } catch (error) {
        this.error = error.response?.data?.message || 'Erreur de connexion'
        return { success: false, error: this.error }
      } finally {
        this.loading = false
      }
    },
    
    async register(name, email, password) {
      this.loading = true
      this.error = null
      
      try {
        const response = await api.register({ name, email, password })
        return { success: true, message: 'Inscription réussie' }
      } catch (error) {
        this.error = error.response?.data?.message || 'Erreur d\'inscription'
        return { success: false, error: this.error }
      } finally {
        this.loading = false
      }
    },
    
    logout() {
      this.user = null
      this.token = null
      this.error = null
    }
  },
  
  persist: {
    paths: ['user', 'token']
  }
})