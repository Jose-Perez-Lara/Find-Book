import api from '@/axios'

export default {
  getServices() {
    return api.get('/services')
  },

  getServiceById(id) {
    return api.get(`/services/${id}`)
  },

  getServicesByNegocio(negocioId) {
    return api.get('/services', {
      params: { negocio: negocioId }
    })
  },

  createService(data) {
    return api.post('/services', data)
  },

  updateService(id, data) {
    return api.patch(`/services/${id}`, data)
  },

  deleteService(id) {
    return api.delete(`/services/${id}`)
  },
}
