
import axios from 'axios'
import { useAuthStore } from '@/utils/auth'

const request = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || '/api',
  headers: {
    "Content-Type": "application/json",
    "Accept": "application/json",
  },
  timeout: 10000,
})

request.interceptors.request.use(
  (config) => {
    const auth = useAuthStore()
    if (auth.token) {
      config.headers.Authorization = `Bearer ${auth.token}`
    }
    return config
  },
  (error) => Promise.reject(error)
)

request.interceptors.response.use(
  (response) => response.data,
  (error) => {
    const auth = useAuthStore()
    if (error.response && error.response.status === 401) {
      auth.clearAuth()
      window.location.href = '/login'
    }
    return Promise.reject(error)
  }
)

export default api;
