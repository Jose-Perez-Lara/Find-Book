import { defineStore } from 'pinia'
import { login as apiLogin, logout as apiLogout, getToken, getUserWithToken } from '@/services/authService'
import NegocioService from '@/services/NegocioService'

export const useAuthStore = defineStore('auth', {
  state: () =>  ({
    token: localStorage.getItem('token') || '',
    user: null, 
    negocio:null
  }),

  getters: {
    isAuthenticated: (state) => !!state.user,
    isNegocio: (state) => state.user?.rol_id == 2
  },

  actions: {
    async login(email, password) {
      const user = await apiLogin(email, password)
      this.token = user.token
      this.initialize(this.token)
    },

    async initialize(token) {
        try {
          const user = await getUserWithToken(token)
          this.user = user
          await NegocioService.getNegociosByUser(this.user.id)
          .then(({data})=>this.negocio = data[0])
        } catch (error) {
          console.error('Error al obtener el usuario:', error)
        }
    },

    async loadUser(){
      this.user = await getUserWithToken()
    },

    logout() {
      apiLogout()
      this.token = ''
      this.user = null
    },
  },
})
