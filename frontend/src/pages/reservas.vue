<template>
  <v-container>
    <v-row justify="center" class="mb-6 mt-8">
      <v-col cols="12" md="8" class="text-center">
        <h2 class="text-h4 font-weight-medium">Aquí tienes tus citas</h2>
      </v-col>
    </v-row>

    <v-sheet
      class="d-flex"
      height="54"
      tile
    >
      <v-select
        v-model="type"
        :items="types"
        class="ma-2"
        label="Modo de visualización"
        density="compact"
        variant="outlined"
        item-title="title"
        item-value="value"
        hide-details
      />
    </v-sheet>

    <v-sheet
      class="mt-4 pa-2"
      elevation="1"
      style="max-height: 70vh; overflow-y: auto;"
    >
      <v-calendar
        v-model="value"
        :events="citas"
        :view-mode="type"
        :interval-duration="30"
        :interval-divisions="1"
        :intervals="24"
        :interval-start="16"
      >
      </v-calendar>
    </v-sheet>
  </v-container>
</template>
<script setup>
    import { ref, onMounted } from 'vue'
    import CitaService from '@/services/CitaService'
    import { useAuthStore } from '@/stores/auth'
    import router from '@/router'
    import { VCalendar } from 'vuetify/labs/VCalendar'

    const value = ref(new Date()) 
    const type = ref('day') 
    const types = [
        { title: 'Días', value: 'day' },
        { title: 'Semana', value: 'week' },
        { title: 'Mes', value: 'month' }
    ]

    const citas = ref([])
    const authStore = useAuthStore()

    const estadoColor = (estado) => {
        switch (estado) {
            case 'confirmada': return 'green'
            case 'pendiente': return '#347c88'
            case 'cancelada': return 'red'
            case 'pasada': return 'grey'
            default: return 'blue'
        }
    }

    const actualizarEstadoCitas = (citasRaw) => {
        const ahora = new Date()
        return citasRaw.map(cita => {
            const fechaHora = new Date(`${cita.fecha}T${cita.hora}`)
            // Modificamos directamente el estado si ya pasó
            if (fechaHora < ahora) cita.estado = 'pasada'

            const end = new Date(fechaHora.getTime() + (cita.servicio?.duracion || 30) * 60000)

            return {
            title: `${cita.cliente?.name || 'Cliente'} - ${cita.servicio?.nombre || 'Servicio'}`,
            subtitle: `${fechaHora} - ${end}`,
            start: fechaHora,
            end: end,
            color: estadoColor(cita.estado),
            }
        })
    }


    onMounted(async () => {
        if (!authStore.negocio) {
            router.push('/')
            return
        }
        try {
            const { data } = await CitaService.getCitasNegocio(authStore.negocio.id)
            citas.value = actualizarEstadoCitas(data)
        } catch (error) {
            console.error('Error al cargar citas:', error)
        }
    })
</script>
