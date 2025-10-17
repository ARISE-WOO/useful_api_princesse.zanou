import { defineStore } from 'pinia'
import api from '../services/api'

export const useModulesStore = defineStore('modules', {
  state: () => ({
    modules: [
      { id: 1, name: 'URL Shortener', key: 'urlShortener', active: false },
      { id: 2, name: 'Wallet', key: 'wallet', active: false },
      { id: 4, name: 'Time Tracker', key: 'timeTracker', active: false }
    ],
    loading: false,
    error: null
  }),
  
  getters: {
    activeModules: (state) => state.modules.filter(m => m.active),
    isModuleActive: (state) => (moduleKey) => {
      return state.modules.find(m => m.key === moduleKey)?.active || false
    }
  },
  
  actions: {
    async fetchModules() {
      this.loading = true
      this.error = null
      
      try {
        const response = await api.getModules()
        this.modules = response.data
      } catch (error) {
        this.error = error.response?.data?.message || 'Erreur de chargement'
      } finally {
        this.loading = false
      }
    },
    
    async toggleModule(moduleId, activate) {
      this.loading = true
      this.error = null
      
      try {
        if (activate) {
          await api.activateModule(moduleId)
        } else {
          await api.deactivateModule(moduleId)
        }
        
        const module = this.modules.find(m => m.id === moduleId)
        if (module) {
          module.active = activate
        }
        
        return { success: true }
      } catch (error) {
        this.error = error.response?.data?.message || 'Erreur de modification'
        return { success: false, error: this.error }
      } finally {
        this.loading = false
      }
    }
  },
  
  persist: {
    paths: ['modules']
  }
})