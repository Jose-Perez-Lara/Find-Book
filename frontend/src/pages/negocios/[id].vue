<template>
  <v-container fluid class="pa-6">
    <v-row>
      <v-col cols="12" md="8">
        <v-img
          v-if="negocio.imagen_portada"
          :src="'http://localhost:8000/'+negocio.imagen_portada"
          class="mb-4 rounded-lg"
          height="200"
          contain
        ></v-img>
        <h1 class="text-h5 font-weight-bold mb-2">{{ negocio.nombre }}</h1>
        <p class="text-subtitle-1 text-grey">{{ negocio.direccion }}</p>
        <p class="text-body-2 mb-4">{{ negocio.descripcion }}</p>

        <h2 class="text-h6 font-weight-bold mb-2">Servicios</h2>
        <v-divider class="mb-4" />

        <v-card
          v-for="servicio in negocio.servicios"
          :key="servicio.id"
          class="mb-3 pa-4"
          outlined
        >
          <v-row align="center" justify="space-between">
            <v-col>
              <h3 class="text-subtitle-1 font-weight-medium">
                {{ servicio.nombre }}
              </h3>
              <p class="text-caption text-grey">
                {{ servicio.duracion }} min
              </p>
            </v-col>
            <v-col class="text-right" cols="4">
              <p class="text-subtitle-1 font-weight-bold mb-2">
                {{ servicio.precio }} €
              </p>
              <v-btn @click="abrirDialog(servicio)" color="#347c88" size="small">Reservar</v-btn>
            </v-col>
          </v-row>
        </v-card>

        <h2 class="text-h6 font-weight-bold mt-8 mb-4">Comentarios</h2>
        <v-list v-if="comentarios.length">
          <v-list-item v-for="comentario in comentarios" :key="comentario.id">
            <v-list-item-avatar>
              <v-icon color="blue lighten-2">mdi-account-circle</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title>{{ comentario.usuario.name }}</v-list-item-title>
              <v-list-item-subtitle>{{ comentario.comentario }}</v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-action>
              <v-rating
                :model-value="comentario.calificacion"
                color="amber"
                dense
                readonly
                size="18"
              ></v-rating>
            </v-list-item-action>
          </v-list-item>
        </v-list>

        <section v-if="authStore.isAuthenticated && tieneReservas" class="mt-6">
          <h3 class="text-h6 font-weight-bold mb-2">Agrega un comentario</h3>
          <v-textarea
            v-model="nuevoComentario.comentario"
            label="Tu comentario"
            rows="3"
            outlined
            dense
          ></v-textarea>

          <v-rating
            v-model="nuevoComentario.calificacion"
            color="amber"
            background-color="grey lighten-2"
            large
            length="5"
          ></v-rating>

          <v-btn
            color="#347c88"
            class="mt-3"
            :loading="loading"
            :disabled="!nuevoComentario.comentario"
            @click="enviarComentario"
          >
            Enviar
          </v-btn>
        </section>
      </v-col>

      <v-col cols="12" md="4">
        <v-card class="pa-4 mb-4" outlined>
          <h3 class="text-subtitle-1 font-weight-bold mb-2">Información del negocio</h3>
          <p><strong>Propietario:</strong> {{ negocio.usuario.name }}</p>
          <p><strong>Teléfono:</strong> {{ negocio.usuario.telefono }}</p>
          <p><strong>Email:</strong> {{ negocio.usuario.email }}</p>
          <div class="bg-white p-4 rounded-xl shadow">
              <p><strong>Ubicación:</strong>{{ negocio.direccion }}</p>
              <iframe
                  v-if="negocio?.direccion"
                  class="w-full rounded-xl"
                  height="200"
                  style="border:0"
                  :src="`https://www.google.com/maps?q=${encodeURIComponent(negocio.direccion)}&output=embed`"
                  allowfullscreen
              ></iframe>
          </div>
        </v-card>
      </v-col>
    </v-row>

    <v-snackbar v-model="snackbar.show" :color="snackbar.color" timeout="3000">
      {{ snackbar.text }}
    </v-snackbar>
    <v-dialog v-model="dialogReservar" max-width="500">
      <v-card>
        <v-card-title>
          Reservar: {{ servicioSeleccionado?.nombre || '' }}
        </v-card-title>

        <v-card-text>
          <v-date-picker
            v-model="fechaSeleccionada"
            :min="minDate"
            @update:modelValue="onFechaSeleccionada"
          />

            <div v-if="cargando" class="text-center my-4">
            <v-progress-circular indeterminate color="primary" />
            </div>

            <div v-else-if="disponibles.length === 0" class="text-center my-4">
            No hay horarios disponibles para esta fecha.
            </div>

            <v-row dense class="mt-4" v-else>
            <v-col
                v-for="(hueco, index) in disponibles"
                :key="index"
                cols="12"
                sm="6"
                md="4"
            >
                <v-btn
                :color="huecoSeleccionado === hueco.inicio ? '#347c88' : 'grey'"
                block
                @click="seleccionarHueco(hueco)"
                >
                {{ hueco.inicio }} - {{ hueco.fin }}
                </v-btn>
            </v-col>
            </v-row>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="dialogReservar = false">Cancelar</v-btn>
          <v-btn
            color="#347c88"
            :disabled="!huecoSeleccionado"
            @click="confirmarReserva"
          >Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import ComentarioService from '@/services/ComentarioService'
import NegocioService from '@/services/NegocioService'
import ServicesService from '@/services/ServicesService'
import HorarioNegocioService from '@/services/HorarioNegocioService'
import CitaService from '@/services/CitaService'

const route = useRoute()
const authStore = useAuthStore()

const negocio = ref({
  nombre: '',
  descripcion: '',
  direccion: '',
  propietario: '',
  telefono: '',
  email: '',
  usuario:{},
  servicios: [],
})

const comentarios = ref([])
const loading = ref(false)
const snackbar = ref({ show: false, text: '', color: 'success' })


const dialogReservar = ref(false)
const servicioSeleccionado = ref(null)
const fechaSeleccionada = ref(null)
const disponibles = ref([])
const cargando = ref(false)
const huecoSeleccionado = ref(null)

const minDate = new Date().toISOString().substr(0, 10) 
const tieneReservas = ref(false)


function abrirDialog(servicio) {
  servicioSeleccionado.value = servicio
  fechaSeleccionada.value = minDate
  huecoSeleccionado.value = null
  disponibles.value = []
  dialogReservar.value = true
  fetchDisponibles()
}

async function fetchDisponibles() {
  if (!fechaSeleccionada.value || !servicioSeleccionado.value) return
  
  cargando.value = true
  disponibles.value = []
  huecoSeleccionado.value = null
  
  try {
    const negocioId = negocio.value.id
    const fechaISO = new Date(fechaSeleccionada.value).toISOString().slice(0, 10);
    console.log(fechaISO)
    const response = await HorarioNegocioService.huecosDisponibles(negocioId, fechaISO, servicioSeleccionado.value.id)
    disponibles.value = response.data.disponibles
  } catch (error) {
    console.error('Error cargando huecos:', error)
    disponibles.value = []
  } finally {
    cargando.value = false
  }
}

function seleccionarHueco(hueco) {
  huecoSeleccionado.value = hueco.inicio
}

function onFechaSeleccionada(value) {
  if (!value) {
    fechaSeleccionada.value = null;
    return;
  }

  const fecha = new Date(value);
  const yyyy = fecha.getFullYear();
  const mm = String(fecha.getMonth() + 1).padStart(2, '0');
  const dd = String(fecha.getDate()).padStart(2, '0');
  fechaSeleccionada.value = `${yyyy}-${mm}-${dd}`;

  fetchDisponibles();
}


async function confirmarReserva() {
  if (!huecoSeleccionado.value || !fechaSeleccionada.value || !servicioSeleccionado.value) return
  
  try { 
    const data = {
      cliente_id: authStore.user.id,
      negocio_id: negocio.value.id,
      servicio_id: servicioSeleccionado.value.id,
      fecha: fechaSeleccionada.value,
      hora: huecoSeleccionado.value,
      duracion: servicioSeleccionado.value.duracion,
    }
    await CitaService.createCita(data)
    
    dialogReservar.value = false
    showSnackbar('Reserva confirmada')
  } catch (error) {
    console.error('Error al confirmar la reserva', error)
    alert('Error al reservar, inténtalo de nuevo')
  }
}
async function verificarReservas() {
  if (!authStore.isAuthenticated) return
  try {
    const { data } = await CitaService.getCitasByNegocioAndUser(negocio.value.id, authStore.user.id)
    tieneReservas.value = data.length > 0
  } catch (error) {
    console.error("Error verificando reservas", error)
    tieneReservas.value = false
  }
}

const nuevoComentario = ref({ comentario: '', calificacion: null })

function showSnackbar(text, color = 'success') {
  snackbar.value = { show: true, text, color }
}

async function cargarNegocio() {
  try {
    const { data:negocios } = await NegocioService.getNegociosById(route.params.id)
    negocio.value = negocios
    const { data: services } = await ServicesService.getServicesByNegocio(negocio.value.id)
    negocio.value.servicios = services
  } catch (error) {
    showSnackbar('Error al cargar el negocio', 'error')
  }
}

async function cargarComentarios() {
  try {
    comentarios.value = await ComentarioService.obtenerComentarios(route.params.id)
  } catch (error) {
    showSnackbar('Error al cargar los comentarios', 'error')
  }
}

async function enviarComentario() {
  loading.value = true
  try {
    await ComentarioService.agregarComentario(
      route.params.id,
      nuevoComentario.value.comentario,
      nuevoComentario.value.calificacion,
      authStore.user.id
    )
    nuevoComentario.value = { comentario: '', calificacion: null }
    await cargarComentarios()
    showSnackbar('Comentario enviado')
  } catch (error) {
    showSnackbar('Error al enviar comentario', 'error')
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  await cargarNegocio()
  await cargarComentarios()
  if(authStore.isAuthenticated){
    await verificarReservas()
  }
})
</script>
