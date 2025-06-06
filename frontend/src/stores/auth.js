import { defineStore } from 'pinia'
import { login as apiLogin, logout as apiLogout, getToken, getUserWithToken } from '@/services/authService'
import NegocioService from '@/services/NegocioService'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: getToken() || '',
    user: null, 
    negocio:null
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isNegocio: (state) => state.user?.rol_id == 2
  },

  actions: {
    async login(email, password) {
      const user = await apiLogin(email, password)
      this.token = user.token
      this.user = user[0]
    },

    async initialize() {
      if (this.token) {
        try {
          const user = await getUserWithToken()
          this.user = user
          await NegocioService.getNegociosByUser(this.user.id)
          .then(({data})=>this.negocio = data[0])
        } catch (error) {
          console.error('Error al obtener el usuario:', error)
        }
      }
    },

    logout() {
      apiLogout()
      this.token = ''
      this.user = null
    },
  },
})
