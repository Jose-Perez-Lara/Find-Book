// src/stores/auth.js
import { defineStore } from 'pinia'
import { login as apiLogin, logout as apiLogout, getToken } from '@/services/authService'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: getToken() || '',
    user: null, 
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isNegocio: (state) => state.user.rol_id == 2
  },

  actions: {
    async login(email, password) {
      const user = await apiLogin(email, password)
      this.token = user.token
      this.user = user[0]
    },

    logout() {
      apiLogout()
      this.token = ''
      this.user = null
    },
  },
})
