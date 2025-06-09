import api from '@/axios'

export default {
    createCita(data){
        return api.post('/citas/', data)
    },
    getCitasByNegocioAndUser(negocioId, userId) {
        return api.get(`/citas/negocio/${negocioId}/usuario/${userId}`)
    }
}