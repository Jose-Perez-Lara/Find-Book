import axios from 'axios'
import { getToken } from './authService'
import { ref } from 'vue'

const API_URL = 'http://127.0.0.1:8000/api'

const token = ref(null)

export default{
    async getNegociosByUser (userId){
        token.value = await getToken()
        return axios.get(`${API_URL}/users/${userId}/negocios`,{headers: {
            Authorization: `Bearer ${token.value}`,
        },})
        
    },

    async updateNegocio(id, newData) {
        token.value = await getToken()
        return axios.patch(`${API_URL}/negocios/${id}`, newData, {
        headers: {
            Authorization: `Bearer ${token.value}`
        }
        })
    },

    async getAllNegocios() {
        token.value = await getToken()
        return axios.get(`${API_URL}/negocios`, {
            headers: {
            Authorization: `Bearer ${token.value}`,
            },
        })
    }

}