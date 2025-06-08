import api from '@/axios'

export default {
  getCategorias() {
    return api.get('/categorias')
  }
}
