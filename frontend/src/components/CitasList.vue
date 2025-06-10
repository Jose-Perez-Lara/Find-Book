<template>
  <v-card
    style="max-height: 70vh; overflow-y: auto;"
  >
    <v-card-title class="text-h6">Listado de Citas</v-card-title>
    <v-card-text>
      <v-data-table
        :headers="headers"
        :items="citas"
        class="elevation-1"
        hide-default-footer
        show-expand
        item-value="id"
      >
        <template #item.usuarioRelacionado="{ item }">
          {{ authStore.isNegocio ? (item.cliente?.name || 'No disponible') : (item.servicio?.negocio.nombre || 'No disponible') }}
        </template>

        <template #item.servicio="{ item }">
          {{ item.servicio?.nombre || 'No disponible' }}
        </template>

        <template #item.fecha="{ item }">
          {{ formatDate(item.fecha) }} a las {{ item.hora }}
        </template>

        <template #item.estado="{ item }">
          <v-chip :color="estadoColor(item.estado)" small>
            {{ item.estado.charAt(0).toUpperCase() + item.estado.slice(1) }}
          </v-chip>
        </template>

        <template v-if="authStore.isNegocio" #item.actions="{ item }">
          <v-btn icon @click="abrirEdicion(item)" :disabled="item.estado === 'pasada'">
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
          <v-btn icon @click="eliminarCita(item)">
            <v-icon color="red">mdi-delete</v-icon>
          </v-btn>
        </template>

        <template #expanded-row="{ columns, item }">
          <tr>
            <td :colspan="columns.length">
              <v-card class="ma-2 pa-3" outlined>
                <template v-if="authStore.isNegocio && item.cliente">
                  <v-card-title>Cliente</v-card-title>
                  <v-card-text>
                    <p><strong>Nombre:</strong> {{ item.cliente.name }}</p>
                    <p><strong>Email:</strong> {{ item.cliente.email }}</p>
                    <p><strong>Teléfono:</strong> {{ item.cliente.telefono || 'No disponible' }}</p>
                  </v-card-text>
                </template>
                <template v-else-if="item.servicio.negocio">
                  <v-card-title>Negocio</v-card-title>
                  <v-card-text>
                    <p><strong>Nombre:</strong> {{ item.servicio.negocio.nombre }}</p>
                    <p><strong>Email:</strong> {{ item.servicio.negocio.usuario?.email }}</p>
                    <p><strong>Teléfono:</strong> {{ item.servicio.negocio.usuario?.telefono || 'No disponible' }}</p>
                    <p><strong>Dirección:</strong> {{ item.servicio.negocio.direccion }}</p>
                  </v-card-text>
                </template>
                <template v-else>
                  <p>Información no disponible.</p>
                </template>
              </v-card>
            </td>
          </tr>
        </template>
      </v-data-table>
    </v-card-text>

    <v-dialog v-model="mostrarDialogo" max-width="500px">
      <v-card>
        <v-card-title>Editar Cita</v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field type="date" v-model="citaEdit.fecha" label="Fecha" />
              </v-col>
              <v-col cols="12">
                <v-text-field type="time" v-model="citaEdit.hora" label="Hora" />
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn color="grey" text @click="mostrarDialogo = false">Cancelar</v-btn>
          <v-btn color="primary" @click="guardarEdicion">Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import CitaService from '@/services/CitaService'
import { useAuthStore } from '@/stores/auth'

const citas = ref([])
const mostrarDialogo = ref(false)
const citaEdit = ref({})
const authStore = useAuthStore()

const headers = computed(() => [
  { title: authStore.isNegocio ? 'Cliente' : 'Negocio', key: 'usuarioRelacionado' },
  { title: 'Servicio', key: 'servicio' },
  { title: 'Fecha y Hora', key: 'fecha' },
  { title: 'Estado', key: 'estado' },
  ...(authStore.isNegocio ? [{ title: 'Acciones', key: 'actions', sortable: false }] : [])
])

const formatDate = (fecha) => new Date(fecha).toLocaleDateString()

const estadoColor = (estado) => {
  switch (estado) {
    case 'confirmada': return 'green'
    case 'pendiente': return 'orange'
    case 'cancelada': return 'red'
    case 'pasada': return 'grey'
    default: return 'blue'
  }
}

const actualizarEstadoCitas = () => {
  const ahora = new Date()
  citas.value.forEach(cita => {
    const fechaHora = new Date(`${cita.fecha}T${cita.hora}`)
    if (fechaHora < ahora) cita.estado = 'pasada'
  })
}

onMounted(async () => {
  try {
    const res = authStore.isNegocio
      ? await CitaService.getCitasNegocio(authStore.negocio.id)
      : await CitaService.getCitasUsuario(authStore.user.id)

    citas.value = res.data
    actualizarEstadoCitas()
  } catch (e) {
    console.error('Error cargando citas:', e)
  }
})

const abrirEdicion = (cita) => {
  citaEdit.value = { ...cita }
  mostrarDialogo.value = true
}

const guardarEdicion = async () => {
  try {
    const { id, fecha, hora } = citaEdit.value
    await CitaService.update(id, { fecha, hora })

    const index = citas.value.findIndex(c => c.id === id)
    if (index !== -1) {
      citas.value[index].fecha = fecha
      citas.value[index].hora = hora
      actualizarEstadoCitas()
    }

    mostrarDialogo.value = false
  } catch (e) {
    console.error('Error actualizando cita:', e)
  }
}

const eliminarCita = async (cita) => {
  if (!confirm("¿Estás seguro de eliminar esta cita?")) return
  try {
    await CitaService.delete(cita.id)
    citas.value = citas.value.filter(c => c.id !== cita.id)
  } catch (error) {
    console.error('Error eliminando cita:', error)
  }
}
</script>