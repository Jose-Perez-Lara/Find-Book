<template>
  <v-card>
    <v-card-title class="text-h6">Listado de Citas</v-card-title>
    <v-card-text>
      <v-data-table
        :headers="headers"
        :items="citas"
        :items-per-page="5"
        class="elevation-1"
        hide-default-footer
        show-expand
        item-value="id"
      >
        <!-- Columna Cliente -->
        <template #item.cliente="{ item }">
          {{ item.cliente?.name || 'No disponible' }}
        </template>

        <!-- Columna Servicio -->
        <template #item.servicio="{ item }">
          {{ item.servicio?.nombre || 'No disponible' }}
        </template>

        <!-- Columna Fecha -->
        <template #item.fecha="{ item }">
          {{ formatDate(item.fecha) }} a las {{ item.hora }}
        </template>

        <!-- Columna Estado -->
        <template #item.estado="{ item }">
          <v-chip :color="estadoColor(item.estado)" small>
            {{ item.estado.charAt(0).toUpperCase() + item.estado.slice(1) }}
          </v-chip>
        </template>

        <!-- Panel Expandible con info del cliente -->
        <template #expanded-row="{ columns, item }">
          <tr>
            <td :colspan="columns.length">
              <v-card class="ma-2 pa-3" outlined>
                <div v-if="item.cliente">
                  <v-card-title>Cliente</v-card-title>
                  <v-card-text>
                    <p><strong>Nombre:</strong> {{ item.cliente.name }}</p>
                    <p><strong>Email:</strong> {{ item.cliente.email }}</p>
                    <p><strong>Teléfono:</strong> {{ item.cliente.telefono || 'No disponible' }}</p>
                  </v-card-text>
                </div>
                <div v-else>
                  <p>Información del cliente no disponible.</p>
                </div>
              </v-card>
            </td>
          </tr>
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import CitaService from '@/services/CitaService'
import { useAuthStore } from '@/stores/auth'

const citas = ref([])

const headers = [
  { title: 'Cliente', key: 'cliente' },
  { title: 'Servicio', key: 'servicio' },
  { title: 'Fecha y Hora', key: 'fecha' },
  { title: 'Estado', key: 'estado' },
]

const authStore = useAuthStore()

const formatDate = (fecha) => {
  return new Date(fecha).toLocaleDateString()
}

const estadoColor = (estado) => {
  switch (estado) {
    case 'confirmada':
      return 'green'
    case 'pendiente':
      return 'orange'
    case 'cancelada':
      return 'red'
    default:
      return 'grey'
  }
}

onMounted(async () => {
  try {
    if (authStore.negocio != null) {
      const res = await CitaService.getCitasNegocio(authStore.negocio.id)
      citas.value = res.data
    } else {
      const res = await CitaService.getCitasUsuario(authStore.user.id)
      citas.value = res.data
    }
  } catch (e) {
    console.error('Error cargando citas:', e)
  }
})
</script>
