import api from '@/axios'

export default {
  getHorarios(negocioId) {
    return api.get(`/horarios-negocio/${negocioId}`)
  },

  crearHorario(horario) {
    return api.post('/horarios-negocio', horario)
  },

  actualizarHorario(id, horario) {
    return api.put(`/horarios-negocio/${id}`, horario)
  },

  eliminarHorario(id) {
    return api.delete(`/horarios-negocio/${id}`)
  },

  async guardarHorariosMasivo(negocioId, horarios) {
    const actuales = await this.getHorarios(negocioId)
    
    await Promise.all(actuales.data.map(h => this.eliminarHorario(h.id)))

    const activos = horarios.filter(h => h.activo)

    return Promise.all(
      activos.map(h =>
        this.crearHorario({
          negocio_id: negocioId,
          dia_semana: h.dia_semana,
          hora_inicio: h.hora_inicio,
          hora_fin: h.hora_fin,
        })
      )
    )
  },
}
