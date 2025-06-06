import axios from 'axios'
import { getToken } from './authService'
import { ref } from 'vue'

const API_URL = 'http://127.0.0.1:8000/api'

const token = ref(null)

export default{
    getNegociosByUser (userId){
        token.value = getToken()
        return axios.get(`${API_URL}/users/${userId}/negocios`,{headers: {
            Authorization: `Bearer ${token.value}`,
        },})
        
    }
}