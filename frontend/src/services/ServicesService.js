import axios from 'axios'
import { getToken } from './authService'

const API_URL = 'http://127.0.0.1:8000/api'

const token = await getToken()

export default{
    getServices(){
        return   axios.get(`${API_URL}/services`)  
    },

    getServiceById(id){
        return axios.get(`${API_URL}/services/${id}`,{headers: {
          Authorization: `Bearer ${token}`,
        },})
    },
    
    getServicesByNegocio(negocioId){
        return axios.get(`${API_URL}/services`, {
            params: {
                negocio: negocioId 
            },
            headers: {
                Authorization: `Bearer ${token}`,
            }
        });
    },

    createService(data){
        return axios.post(`${API_URL}/services`, data,{
            headers: {
                Authorization: `Bearer ${token}`,
            }
        })
    },
    
    updateService(id, data){
        return  axios.patch(`${API_URL}/services/${id}`, data,{
            headers: {
                Authorization: `Bearer ${token}`,
            }
        })
    },
    
    deleteService(id){
        return axios.delete(`${API_URL}/services/${id}`,{
            headers: {
                Authorization: `Bearer ${token}`,
            }
        })
    },
}
