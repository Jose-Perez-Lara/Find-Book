import api from '@/axios'

export default {
    createCita(data){
        return api.post('/citas/', data)
    },
    getCitasByNegocioAndUser(negocioId, userId) {
        return api.get(`/citas/negocio/${negocioId}/usuario/${userId}`)
    },
    getCitasNegocio(negocioId){
        return api.get('/citas/?negocio_id=' + negocioId )
    },
    getCitasUsuario(userId){
        return api.get('/citas/?usuario_id=' + userId )
    },
    update(id, data) {
        return api.put(`/citas/${id}`, data)
    },
    delete(id){
        return api.delete('/citas/' + id)
    }
}