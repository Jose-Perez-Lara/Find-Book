import axios from 'axios'
import { getToken } from './authService'
import { ref } from 'vue'

const API_URL = 'http://127.0.0.1:8000/api'

const token = ref(null)

export default{
    async updateUser(userId, data){
        token.value = await getToken()
        return axios.patch(`${API_URL}/users/${userId}`, data, {
            headers: {
            Authorization: `Bearer ${token.value}`
            }
        })
    }
}