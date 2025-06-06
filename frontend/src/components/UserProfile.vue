<template>
  <v-container class="py-8" fluid>
    <v-card class="mx-auto mb-4" max-width="600" color="#FFFFFF" theme="light">
      <v-card-title class="d-flex justify-space-between align-center" color="teal lighten-3">
        Perfil de {{ profile.name }}
        <v-btn variant="flat" color="#347c88" size="small" @click="dialogPerfil = true">
          Editar Perfil
        </v-btn>
      </v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <v-list dense>
          <v-list-item>
            <v-list-item-title>Correo</v-list-item-title>
            <v-list-item-subtitle>{{ profile.email }}</v-list-item-subtitle>
          </v-list-item>
          <v-list-item>
            <v-list-item-title>Teléfono</v-list-item-title>
            <v-list-item-subtitle>{{ profile.telefono }}</v-list-item-subtitle>
          </v-list-item>
        </v-list>
      </v-card-text>
    </v-card>

    <v-card v-if="authStore.isNegocio" class="mx-auto" max-width="600" color="#FFFFFF" theme="light">
      <v-card-title class="d-flex justify-space-between align-center" color="blue lighten-4">
        Negocio: {{ negocio.nombre }}
        <v-btn variant="flat" color="#347c88" size="small" @click="dialogNegocio = true">
          Editar Negocio
        </v-btn>
      </v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <v-list dense>
          <v-list-item>
            <v-list-item-title>Descripción</v-list-item-title>
            <v-list-item-subtitle>{{ negocio.descripcion }}</v-list-item-subtitle>
          </v-list-item>
          <v-list-item>
            <v-list-item-title>Categoría</v-list-item-title>
            <v-list-item-subtitle>{{ negocio.categoria?.nombre }}</v-list-item-subtitle>
          </v-list-item>
        </v-list>
      </v-card-text>
    </v-card>

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

    <v-dialog v-model="dialogNegocio" max-width="500">
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
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn text @click="dialogNegocio = false" color="grey lighten-1">Cancelar</v-btn>
          <v-btn color="#347c88" @click="saveNegocio">Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import CategoriaService from '@/services/CategoriaService'

const authStore = useAuthStore()
const profile = ref({})
const negocio = ref({})
const categorias = ref([])
const dialogPerfil = ref(false)
const dialogNegocio = ref(false)

const formPerfil = ref({
  name: '',
  email: '',
  telefono: ''
})

const formNegocio = ref({
  nombre: '',
  descripcion: '',
  categoria: '',
  color_tema: ''
})

watch(
  () => authStore.user,
  () => {
    profile.value = { ...authStore.user }
    negocio.value = { ...authStore.negocio }
    setFormValues()
  }
)

onMounted(async () => {
  profile.value = { ...authStore.user }
  negocio.value = { ...authStore.negocio }
  
  await CategoriaService.getCategorias().then(({data})=>{
    categorias.value = data[0]
    
  })
  setFormValues()
})

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
    }
  }
}

function savePerfil() {
  console.log('Guardando perfil:', formPerfil.value)
  dialogPerfil.value = false
}

function saveNegocio() {
  console.log('Guardando negocio:', formNegocio.value)
  dialogNegocio.value = false
}
</script>