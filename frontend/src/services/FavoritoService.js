import api from '@/axios'

export default {
    getFavoritos(userId){
        return api.get('/favoritos/' + userId)
    },
    toggleFavorito(negocioId, userId){
        return api.post('/favoritos/toggle', {negocio_id: negocioId, user_id: userId})
    }
}