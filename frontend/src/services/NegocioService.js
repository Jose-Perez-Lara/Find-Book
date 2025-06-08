import api from '@/axios'

export default {
  getNegociosByUser(userId) {
    return api.get(`/users/${userId}/negocios`)
  },

  getNegociosById(negocioId) {
    return api.get(`/negocios/${negocioId}`)
  },

  updateNegocio(id, newData) {
    return api.patch(`/negocios/${id}`, newData)
  },

  getAllNegocios() {
    return api.get('/negocios')
  }
}
