<template>
  <v-container 
    class="py-10" 
    fluid 
    style="max-height: 70vh; overflow-y: auto;"
  >
    <v-row justify="center" class="mb-6">
      <v-col cols="12" md="8" class="text-center">
        <h2 class="text-h4 font-weight-medium">Bienvenido de nuevo, <span class="text-teal darken-2">{{ profile.name }}</span></h2>
      </v-col>
    </v-row>

    <v-row justify="center">
      <v-col cols="12" md="8">
        <v-card elevation="6" rounded class="pa-6 mb-8" color="#fafafa">
          <v-card-title class="d-flex justify-space-between align-center pa-0 mb-4">
            <h3 class="text-subtitle-1 font-weight-bold teal--text">Tu Perfil</h3>
            <v-btn variant="outlined" color="teal" small @click="dialogPerfil = true" rounded>
              <v-icon left size="18">mdi-account-edit</v-icon> Editar Perfil
            </v-btn>
          </v-card-title>
          
          <div style="height: 2px; background-color: #47a8a8;"></div>

          <v-list dense>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title class="text-body-2 font-weight-medium">Correo</v-list-item-title>
                <v-list-item-subtitle class="text-subtitle-2">{{ profile.email }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>

            <v-list-item>
              <v-list-item-content>
                <v-list-item-title class="text-body-2 font-weight-medium">Teléfono</v-list-item-title>
                <v-list-item-subtitle class="text-subtitle-2">{{ profile.telefono }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card>

        <v-card v-if="authStore.isNegocio" elevation="6" rounded class="pa-6" color="#f9fbfc">
          <v-card-title class="d-flex justify-space-between align-center pa-0 mb-4">
            <div>
              <h3 class="text-subtitle-1 font-weight-bold blue--text">Negocio: {{ negocio.nombre }}</h3>
            </div>
            <div class="d-flex gap-3">
              <v-btn variant="outlined" color="#347c88" small @click="dialogNegocio = true" rounded>
                <v-icon left size="18">mdi-store-edit</v-icon> Editar Negocio
              </v-btn>
              <v-btn variant="outlined" color="#347c88" small @click="dialogHorarios = true" rounded>
                <v-icon left size="18">mdi-calendar-clock</v-icon> Editar Horarios
              </v-btn>
              <v-btn variant="outlined" color="#347c88" small @click="dialogImagen = true" rounded>
                <v-icon left size="18">mdi-image-plus</v-icon> Agregar Imagen
              </v-btn>
            </div>
          </v-card-title>
          
          <div style="height: 2px; background-color: #6794a7;"></div>

          <v-list dense>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title class="text-body-2 font-weight-medium">Descripción</v-list-item-title>
                <v-list-item-subtitle class="text-subtitle-2">{{ negocio.descripcion }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>

            <v-list-item>
              <v-list-item-content>
                <v-list-item-title class="text-body-2 font-weight-medium">Categoría</v-list-item-title>
                <v-list-item-subtitle class="text-subtitle-2">{{ negocio.categoria?.nombre }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>

            <v-list-item>
              <v-list-item-content>
                <v-list-item-title class="text-body-2 font-weight-medium">Ubicación</v-list-item-title>
                <v-list-item-subtitle class="text-subtitle-2">
                  <iframe
                      v-if="negocio?.direccion"
                      class="w-full rounded-xl"
                      height="200"
                      style="border:0"
                      :src="`https://www.google.com/maps?q=${encodeURIComponent(negocio.direccion)}&output=embed`"
                      allowfullscreen
                  ></iframe>
                </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card>
      </v-col>
    </v-row>


    <v-dialog v-model="dialogPerfil" max-width="500">
      <v-card>
        <v-card-title class="text-h6" color="teal lighten-3">Editar Perfil</v-card-title>
        <v-card-text>
          <v-text-field v-model="formPerfil.name" label="Nombre" color="#347c88" />
          <v-text-field v-model="formPerfil.email" label="Correo" color="#347c88" />
          <v-text-field v-model="formPerfil.telefono" label="Teléfono" color="#347c88" />
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn text @click="dialogPerfil = false" color="grey lighten-1">Cancelar</v-btn>
          <v-btn color="#347c88" @click="savePerfil">Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialogNegocio" max-width="700">
      <v-card>
        <v-card-title class="text-h6" color="#347c88">Editar Negocio</v-card-title>
        <v-card-text>
          <v-text-field v-model="formNegocio.nombre" label="Nombre del negocio" color="#347c88" />
          <v-text-field v-model="formNegocio.descripcion" label="Descripción" color="#347c88" />
          
          <v-select
            v-model="formNegocio.categoria"
            :items="categorias"
            label="Categoría"
            item-title="nombre"
            item-value="id"
            prepend-inner-icon="mdi-tag"
            required
            clearable
          />

          <v-text-field
            v-model="formNegocio.direccion"
            label="Dirección"
            prepend-inner-icon="mdi-map-marker"
            color="#347c88"
          />

          <div id="map-negocio" style="height: 300px; margin-top: 15px; border-radius: 8px; overflow: hidden;"></div>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn text @click="dialogNegocio = false" color="grey lighten-1">Cancelar</v-btn>
          <v-btn color="#347c88" @click="saveNegocio">Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogHorarios" max-width="700">
      <v-card>
        <v-card-title class="text-h6" color="#347c88">Editar Horarios</v-card-title>
        <v-card-text>
          <v-list>
            <v-list-item
              v-for="(dia, index) in horarios"
              :key="index"
              class="pa-0"
            >
              <v-list-item-content class="py-2">
                <v-row align="center">
                  <v-col cols="12">
                    <v-checkbox
                      v-model="dia.activo"
                      :label="diasSemana[dia.dia_semana - 1]"
                      hide-details
                      density="compact"
                    />
                  </v-col>
                </v-row>

                <div v-if="dia.activo">
                  <div
                    v-for="(bloque, i) in dia.bloques"
                    :key="i"
                  >
                    <v-row dense>
                      <v-col cols="5">
                        <v-text-field
                          v-model="bloque.hora_inicio"
                          label="Inicio"
                          type="time"
                          dense
                          hide-details
                        />
                      </v-col>
                      <v-col cols="5">
                        <v-text-field
                          v-model="bloque.hora_fin"
                          label="Fin"
                          type="time"
                          dense
                          hide-details
                        />
                      </v-col>
                      <v-col cols="2" class="text-right">
                        <v-btn icon @click="eliminarBloque(dia.dia_semana, i)">
                          <v-icon>mdi-delete</v-icon>
                        </v-btn>
                      </v-col>
                    </v-row>
                  </div>

                  <v-btn
                    small
                    color="#347c88"
                    class="mt-2"
                    @click="agregarBloque(dia.dia_semana)"
                  >
                    <v-icon left>mdi-plus</v-icon>
                    Agregar bloque
                  </v-btn>
                </div>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn text @click="dialogHorarios = false" color="blue-grey-lighten-1">Cancelar</v-btn>
          <v-btn color="#347c88" @click="saveHorarios">Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogImagen" max-width="500">
      <v-card>
        <v-card-title class="text-h6" color="#347c88">Agregar Imagen al Negocio</v-card-title>
        <v-card-text>
          <v-file-input
            v-model="imagenNegocio"
            label="Selecciona una imagen"
            accept="image/*"
            show-size
            clearable
            prepend-icon="mdi-image"
          />
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn text @click="dialogImagen = false" color="grey lighten-1">Cancelar</v-btn>
          <v-btn color="#347c88" @click="uploadImagen">Subir Imagen</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </v-container>
  <v-snackbar
    v-model="snackbar.show"
    :color="snackbar.color"
    timeout="3000"
  >
    {{ snackbar.text }}
  </v-snackbar>
</template>

<script setup>
  import { ref, watch, onMounted } from 'vue';
  import { useAuthStore } from '@/stores/auth';
  import CategoriaService from '@/services/CategoriaService';
  import HorarioNegocioService from '@/services/HorarioNegocioService';
  import NegocioService from '@/services/NegocioService';
  import PerfilService from '@/services/PerfilService';
  import L from 'leaflet'
  import 'leaflet/dist/leaflet.css'
  import { nextTick } from 'vue'


  const authStore = useAuthStore()
  const profile = ref({})
  const negocio = ref({})
  const categorias = ref([])
  const dialogPerfil = ref(false)
  const dialogNegocio = ref(false)
  const dialogHorarios = ref(false)
  const horarios = ref([])
  const map = ref(null)
  const marker = ref(null)
  const dialogImagen = ref(false)
  const imagenNegocio = ref(null)

  
  
  const formPerfil = ref({
    name: '',
    email: '',
    telefono: ''
  })

  const diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']
  
  
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


  const formNegocio = ref({
    nombre: '',
    descripcion: '',
    categoria: '',
    direccion: '',
    latitud: null,
    longitud: null,
  })

  function agregarBloque(dia_semana) {
    const dia = horarios.value.find(d => d.dia_semana === dia_semana)
    if (dia) {
      dia.bloques.push({
        hora_inicio: '09:00',
        hora_fin: '17:00'
      })
    }
  }
  
  function eliminarBloque(dia_semana, index) {
    const dia = horarios.value.find(d => d.dia_semana === dia_semana)
    if (dia && dia.bloques[index]) {
      dia.bloques.splice(index, 1)
    }
  }
  
  watch(
    () => authStore.user,
    () => {
      profile.value = { ...authStore.user }
      setFormValues()
    }
  )
  watch(
    () => authStore.negocio,
    () => {
      negocio.value = { ...authStore.negocio }
      setFormValues()
    }
  )
  
  onMounted(async () => {
    profile.value = { ...authStore.user }
    negocio.value = { ...authStore.negocio }
    
    await CategoriaService.getCategorias().then(({ data }) => {
      categorias.value = data[0]
    })
    
    inicializarHorariosPorDefecto()
    setFormValues()
    
    if (authStore.isNegocio && authStore.negocio?.id) {
      const { data: horariosDb } = await HorarioNegocioService.getHorarios(authStore.negocio.id)
      for (const h of horariosDb) {
        const dia = horarios.value.find(d => d.dia_semana === h.dia_semana)
        if (dia) {
          dia.activo = true
          dia.bloques.push({
            hora_inicio: h.hora_inicio,
            hora_fin: h.hora_fin
          })
        }
      }
    }
  })
  
  async function uploadImagen() {
    if (!imagenNegocio.value) {
      showSnackbar('Por favor selecciona una imagen', 'error')
      return
    }

    try {
      await NegocioService.uploadImagen(negocio.value.id, imagenNegocio.value)
      showSnackbar('Imagen subida correctamente', 'green-lighten-1')
      dialogImagen.value = false
      imagenNegocio.value = null
    } catch (error) {
      console.error('Error subiendo imagen:', error)
      showSnackbar('Error al subir la imagen', 'error')
    }
  }


  function inicializarHorariosPorDefecto() {
    horarios.value = Array.from({ length: 7 }, (_, i) => ({
      dia_semana: i + 1,
      activo: false,
      bloques: [] // sin bloques al inicio
    }))
  }

  function setFormValues() {
    if (!profile.value) return

    formPerfil.value = {
      name: profile.value.name || '',
      email: profile.value.email || '',
      telefono: profile.value.telefono || ''
    }

    if (negocio.value) {
      formNegocio.value = {
        nombre: negocio.value.nombre || '',
        descripcion: negocio.value.descripcion || '',
        categoria: negocio.value.categoria_id || '',
        direccion: negocio.value.direccion || '',
        latitud: negocio.value.latitud || 37.3886,
        longitud: negocio.value.longitud || -5.9823,
      }
    }
  }


  async function savePerfil() {
    try {
      await PerfilService.updateUser(profile.value.id, formPerfil.value)
      authStore.loadUser()
      showSnackbar('Perfil actualizado correctamente', 'green-lighten-1')
    } catch (error) {
      console.error('Error al actualizar perfil:', error)
      showSnackbar('Error al actualizar perfil', 'error')
    }
    dialogPerfil.value = false
  }


  async function actualizarDireccionDesdeCoords(lat, lng) {
    try {
      const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`);
      const data = await response.json();

      if (data && data.display_name) {
        formNegocio.value.direccion = data.display_name;
      }
    } catch (error) {
      console.error("Error obteniendo dirección inversa:", error);
    }
  }


  watch(dialogNegocio, (val) => {
  if (val) {
    nextTick(() => {
      setTimeout(() => {
        inicializarMapaNegocio()
      }, 400)
    })
  }
})


 async function saveNegocio() {
    try {
      await NegocioService.updateNegocio(authStore.negocio?.id, formNegocio.value)
      showSnackbar('Negocio actualizado correctamente', 'green-lighten-1')
      dialogNegocio.value = false
    } catch (error) {
      console.error('Error al actualizar negocio:', error)
      showSnackbar('Error al actualizar negocio', 'error')
    }
  }

  function formatoHoraSinSegundos(hora) {
    return hora?.slice(0, 5) // solo: "HH:mm", por que si no los horarios activos me llegan como "HH:mm:ss"
  }

  async function saveHorarios() {
    try {
      const horariosActivos = []

      horarios.value.forEach(dia => {
        if (dia.activo) {
          dia.bloques.forEach(bloque => {
            horariosActivos.push({
              dia_semana: dia.dia_semana,
              hora_inicio: formatoHoraSinSegundos(bloque.hora_inicio),
              hora_fin: formatoHoraSinSegundos(bloque.hora_fin)
            })
          })
        }
      })

      await HorarioNegocioService.guardarHorariosMasivo(authStore.negocio.id, horariosActivos)
      dialogHorarios.value = false
      showSnackbar('Horarios guardados correctamente', 'green-lighten-1')
    } catch (err) {
      console.error('Error guardando horarios:', err)
      showSnackbar('Error al guardar los horarios', 'error')
    }
  }


  function inicializarMapaNegocio() {
    const lat = formNegocio.value.latitud || 37.3886;
    const lng = formNegocio.value.longitud || -5.9823;

    if (map.value) {
      map.value.remove();
    }

    map.value = L.map("map-negocio").setView([lat, lng], 14);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map.value);

    marker.value = L.marker([lat, lng], { draggable: true }).addTo(map.value);

    marker.value.on("dragend", function (e) {
      const pos = e.target.getLatLng();
      formNegocio.value.latitud = pos.lat;
      formNegocio.value.longitud = pos.lng;
      actualizarDireccionDesdeCoords(pos.lat, pos.lng);
    });

    map.value.on("click", function (e) {
      const { lat, lng } = e.latlng;
      formNegocio.value.latitud = lat;
      formNegocio.value.longitud = lng;
      marker.value.setLatLng([lat, lng]);
      actualizarDireccionDesdeCoords(lat, lng);
    });
  }
</script>