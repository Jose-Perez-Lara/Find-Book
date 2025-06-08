import axios from 'axios'
import { getToken } from './authService'
import { ref } from 'vue'

const API_URL = 'http://127.0.0.1:8000/api'

const token = ref(getToken())


export default {
  getHorarios(negocioId) {
    return axios.get(`${API_URL}/horarios-negocio/${negocioId}`,{headers: {
            Authorization: `Bearer ${token.value}`,
        },})
  },

  crearHorario(horario) {
    return axios.post(`${API_URL}/horarios-negocio`, horario,{headers: {
            Authorization: `Bearer ${token.value}`,
        },})
  },

  actualizarHorario(id, horario) {
    return axios.put(`${API_URL}/horarios-negocio/${id}`, horario,{headers: {
            Authorization: `Bearer ${token.value}`,
        },})
  },

  eliminarHorario(id) {
    return axios.delete(`${API_URL}/horarios-negocio/${id}`,{headers: {
            Authorization: `Bearer ${token.value}`,
        },})
  },

  async guardarHorariosMasivo(negocioId, horarios) {
    const { data: actuales } = await this.getHorarios(negocioId)

    await Promise.all(actuales.map(h => this.eliminarHorario(h.id)))

    const activos = horarios.filter(h => h.activo)

    return Promise.all(
      activos.map(h => this.crearHorario({
        negocio_id: negocioId,
        dia_semana: h.dia_semana,
        hora_inicio: h.hora_inicio,
        hora_fin: h.hora_fin
      }))
    )
  }
}
