import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  
  state: () => ({
    token: sessionStorage.getItem('api_token') || null,
    user: JSON.parse(sessionStorage.getItem('user')) || null,
  }),
  actions: {
    setAuth(token, user) {
      this.token = token
      this.user = user
      sessionStorage.setItem('api_token', token)
      sessionStorage.setItem('user', JSON.stringify(user))
    },
    clearAuth() {
      this.token = null
      this.user = null
      sessionStorage.removeItem('api_token')
      sessionStorage.removeItem('user')
    },
  },
  getters: {
    isAuthenticated: (state) => !!state.token,
  },
})