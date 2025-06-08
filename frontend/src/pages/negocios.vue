<template>
  <v-container fluid class="pa-6">
    <v-row justify="center">
      <v-col cols="12" md="8">
        <v-card elevation="6" class="pa-6" color="#f5f5f5">
          <v-card-title class="text-h5 font-weight-bold blue--text">
            {{ negocio.nombre }}
          </v-card-title>
          <v-card-subtitle class="mb-4">
            <em>{{ negocio.descripcion }}</em>
          </v-card-subtitle>

          <v-divider></v-divider>

          <section class="mt-6">
            <h3 class="text-h6 font-weight-bold mb-4">Comentarios</h3>

            <v-list two-line>
              <v-list-item v-for="comentario in comentarios" :key="comentario.id">
                <v-list-item-avatar>
                  <v-icon color="blue lighten-2">mdi-account-circle</v-icon>
                </v-list-item-avatar>

                <v-list-item-content>
                  <v-list-item-title>{{ comentario.usuario.name }}</v-list-item-title>
                  <v-list-item-subtitle>
                    {{ comentario.comentario }}
                  </v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-action>
                  <v-rating
                    :value="comentario.calificacion"
                    color="amber"
                    dense
                    readonly
                    size="18"
                  ></v-rating>
                </v-list-item-action>
              </v-list-item>
            </v-list>

            <v-alert
              v-if="comentarios.length === 0"
              type="info"
              border="left"
              colored-border
              elevation="2"
            >
              No hay comentarios todavía.
            </v-alert>
          </section>

          <section v-if="authStore.isLoggedIn" class="mt-6">
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
              color="blue"
              class="mt-3"
              :loading="loading"
              :disabled="!nuevoComentario.comentario"
              @click="enviarComentario"
            >
              Enviar
            </v-btn>
          </section>
        </v-card>
      </v-col>
    </v-row>

    <v-snackbar v-model="snackbar.show" :color="snackbar.color" timeout="3000">
      {{ snackbar.text }}
    </v-snackbar>
  </v-container>
</template>

<script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute } from 'vue-router';
  import { useAuthStore } from '@/stores/auth';
  import comentariosService from '@/services/comentarios';
  import axios from 'axios';

  const route = useRoute()

  const negocio = ref({})
  const comentarios = ref([])
  const loading = ref(false)
  const snackbar = ref({
    show: false,
    text: '',
    color: 'success',
  })

  const nuevoComentario = ref({
    comentario: '',
    calificacion: null,
  })

  function showSnackbar(text, color = 'success') {
    snackbar.value.text = text
    snackbar.value.color = color
    snackbar.value.show = true
  }

  async function cargarNegocio() {
    try {
      const { data } = await axios.get(`http://127.0.0.1:8000/api/negocios/${route.params.id}`)
      negocio.value = data
    } catch (error) {
      showSnackbar('Error al cargar el negocio', 'error')
    }
  }

  async function cargarComentarios() {
    try {
      comentarios.value = await comentariosService.obtenerComentarios(route.params.id)
    } catch (error) {
      showSnackbar('Error al cargar los comentarios', 'error')
    }
  }

  async function enviarComentario() {
    loading.value = true
    try {
      const token = authStore.token
      await comentariosService.agregarComentario(
        route.params.id,
        nuevoComentario.value.comentario,
        nuevoComentario.value.calificacion,
        token
      )
      nuevoComentario.value.comentario = ''
      nuevoComentario.value.calificacion = null
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
  })

</script>