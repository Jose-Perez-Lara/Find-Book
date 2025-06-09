import api from '@/axios'

export default {
  async obtenerComentarios(negocioId) {
    const response = await api.get(`/negocios/${negocioId}/comentarios`)
    return response.data
  },

  async agregarComentario(negocioId, comentario, calificacion, userId) {
    const response = await api.post(`/negocios/${negocioId}/comentarios`, {
      comentario,
      calificacion,
      userId
    })
    return response.data
  },
}
