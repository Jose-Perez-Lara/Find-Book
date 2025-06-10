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
  },

  getNegociosFavoritos(userId){
    return api.get('/negocios/favoritos/' + userId)
  },

  uploadImagen(negocioId, file) {
    const formData = new FormData()
    formData.append('imagen', file)
    return api.post(`/negocios/${negocioId}/imagen`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
  }

}
