<template>
  <v-card class="pa-6 mb-8" elevation="6" rounded="xl" color="#f0f4f5">
    <v-card-title
      class="d-flex justify-space-between align-center pa-0"
      style="margin-bottom: 10px;"
    >
      <span class="text-title-1 font-weight-bold teal--text">Lista de Servicios</span>
      <v-btn
        color="#347c88"
        rounded
        class="ml-4"
        style="min-width: 180px;"
        @click="dialog = true"
      >
        <v-icon left size="18">mdi-plus</v-icon>
        Agregar Servicio
      </v-btn>
    </v-card-title>
    <div style="height: 2px; background-color: #347c88;"></div>

    <v-card-text class="pt-2 pb-6">
      <v-list bg-color="#f0f4f5">
        <v-list-item
          v-for="service in services"
          :key="service.id"
          class="mb-4 rounded-xl"
          style="border: 1px solid #d2e4e6; padding: 16px;"
        >
          <v-list-item-content>
            <v-list-item-title class="text-body-1 font-weight-bold mb-1">
              {{ service.nombre }} - {{ service.precio }}€
            </v-list-item-title>
            <v-list-item-subtitle class="text-subtitle-2 mb-2">
              {{ service.descripcion }}
            </v-list-item-subtitle>
            <div class="text-caption text-grey-darken-1 mb-2">
              Duración: {{ service.duracion }} min
            </div>
          </v-list-item-content>

          <v-list-item-action class="d-flex gap-2">
            <v-btn
              icon
              size="small"
              @click="editarServicio(service)"
              color="#347c88"
              variant="text"
            >
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
            <v-btn
              icon
              size="small"
              @click="eliminarServicio(service.id)"
              color="red-lighten-1"
              variant="text"
            >
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </v-list-item-action>
        </v-list-item>
      </v-list>
    </v-card-text>
  </v-card>


    <v-dialog
      v-model="dialog" 
      max-width="500"
      transition="dialog-bottom-transition"
    >
      <v-card>
        <v-card-title>
          <span class="text-h6" >{{ modoEdicion ? 'Actualizar Servicio' : 'Agregar Servicio' }}</span>
        </v-card-title>
        <v-card-text>
          <v-form>
            <v-text-field 
              label="Nombre" 
              v-model="nuevoServicio.nombre" 
              prepend-inner-icon="mdi-tag"
              required/>
            <v-textarea
              label="Descripción"
              v-model="nuevoServicio.descripcion" 
              prepend-inner-icon="mdi-note-text"
              auto-grow
              rows="3"
              required/>
            <v-text-field
              label="Precio"
              v-model.number="nuevoServicio.precio"
              type="number"
              min="0"
              prepend-inner-icon="mdi-currency-eur"
              required
            />
            <v-select
              v-model="nuevoServicio.duracion"
              :items="[
                { text: '15 minutos', value: 15 },
                { text: '30 minutos', value: 30 },
                { text: '45 minutos', value: 45 },
                { text: '1 hora', value: 60 },
                { text: '1 hora 15 minutos', value: 75 },
                { text: '1 hora 30 minutos', value: 90 },
                { text: '1 hora 45 minutos', value: 105 },
                { text: '2 horas', value: 120 }
              ]"
              label="Duración del servicio"
              item-title="text"
              item-value="value"
              required
            />
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn text @click="cerrarDialogo">Cancelar</v-btn>
          <v-btn color="#347c88" @click="guardarServicio">
            {{ modoEdicion ? 'Actualizar' : 'Guardar' }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-snackbar
      v-model="snackbar.show"
      :color="snackbar.color"
      timeout="3000"
    >
      {{ snackbar.text }}
    </v-snackbar>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import ServicesService from '@/services/ServicesService';
import NegocioService from '@/services/NegocioService';

const authStore = useAuthStore()
const services = ref([])
const dialog = ref(false)
const modoEdicion = ref(false)
const negocioId = ref(null)
const nuevoServicio = ref({
  'nombre': '',
  'descripcion': '',
  'precio': 0,
  'duracion': 0,
})

const snackbar = ref({
  show: false,
  text: '',
  color: 'green-lighten-1'
})

function showSnackbar(text, color = 'green-lighten-1') {
  snackbar.value.text = text
  snackbar.value.color = color
  snackbar.value.show = true
}

const guardarServicio = async () => {
  if (!negocioId.value) return

  try {
    if (modoEdicion.value) {
      await ServicesService.updateService(nuevoServicio.value.id, {
        ...nuevoServicio.value,
        negocio_id: negocioId.value
      })
      showSnackbar('Servicio actualizado exitosamente', 'green-lighten-1')
    } else {
      await ServicesService.createService({
        ...nuevoServicio.value,
        negocio_id: negocioId.value
      })
      showSnackbar('Servicio creado exitosamente', 'green-lighten-1')
    }

    const { data } = await ServicesService.getServicesByNegocio(negocioId.value)
    services.value = data

    dialog.value = false
    modoEdicion.value = false
    nuevoServicio.value = {
      nombre: '',
      descripcion: '',
      precio: 0,
      duracion: 0
    }
  } catch (error) {
    console.error('Error al guardar el servicio:', error)
    showSnackbar('Error al guardar el servicio', 'red-lighten-1')
  }
}

const cerrarDialogo = () => {
  dialog.value = false
  modoEdicion.value = false
  nuevoServicio.value = {
    nombre: '',
    descripcion: '',
    precio: 0,
    duracion: 0
  }
}

const editarServicio = (servicio) => {
  modoEdicion.value = true
  nuevoServicio.value = { ...servicio }
  dialog.value = true
}

const eliminarServicio = async (id) => {
  try {
    await ServicesService.deleteService(id)
    const { data } = await ServicesService.getServicesByNegocio(negocioId.value)
    services.value = data
    showSnackbar('Servicio eliminado correctamente', 'green-lighten-1')
  } catch (error) {
    console.error('Error al eliminar servicio:', error)
    showSnackbar('Error al eliminar servicio', 'error')
  }
}


onMounted(()=>{
  if(authStore.isNegocio){
    NegocioService.getNegociosByUser(authStore.user.id)
    .then(({data})=>{
      negocioId.value = data[0].id
      return ServicesService.getServicesByNegocio(data[0].id)
    })
    .then(({data})=> services.value = data)
  }
})

</script>
