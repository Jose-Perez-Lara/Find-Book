<template>
  <v-container>
    <v-row justify="center" align="center" class="ma-0">
      <v-col cols="12" sm="8" md="4">
        <v-card class="pa-6" elevation="4" rounded="xl">
          <div class="text-center mb-6 d-flex justify-center">
            <v-img
              src="@/assets/logo.png"
              contain
              max-width="56"
              max-height="56"
              class="mb-2"
            />
            <div class="text-h4 font-weight-bold mt-2">Find&Book</div>
          </div>

          <v-card-title class="text-h4 mb-4" style="white-space: normal; text-align: center;">
            Solicita el registro de tu empresa
          </v-card-title>

          <v-form v-model="valid" @submit.prevent="submitForm">
            <v-text-field
              v-model="business.email"
              label="Correo electrónico"
              :rules="emailRules"
              prepend-inner-icon="mdi-email"
              required
            />
            <v-text-field
              v-model="business.name"
              label="Nombre del solicitante"
              prepend-inner-icon="mdi-account"
              required
            />
            <v-text-field
              v-model="business.telefono"
              label="Teléfono"
              :counter="9"
              prepend-inner-icon="mdi-phone"
              required
            />

            <v-text-field
              v-model="business.nombre_negocio"
              label="Nombre del negocio"
              prepend-inner-icon="mdi-storefront"
              required
            />
            <v-select
              v-model="business.categoria_id"
              :items="categorias.data"
              label="Categoría"
              item-title="nombre"
              item-value="id"
              prepend-inner-icon="mdi-tag"
              required
            />
            <v-textarea
              v-model="business.descripcion"
              label="Descripción del negocio"
              auto-grow
              prepend-inner-icon="mdi-note-text"
              rows="3"
              required
            />
            <v-text-field
              v-model="password"
              label="Contraseña"
              :rules="passwordRules"
              prepend-inner-icon="mdi-lock"
              :type="showPassword ? 'text' : 'password'"
              :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
              @click:append="showPassword = !showPassword"
              required
            />
            <v-text-field
              v-model="confirmPassword"
              label="Confirmar contraseña"
              :rules="confirmPasswordRule"
              prepend-inner-icon="mdi-lock"
              :type="showConfirmPassword ? 'text' : 'password'"
              :append-icon="showConfirmPassword ? 'mdi-eye-off' : 'mdi-eye'"
              @click:append="showConfirmPassword = !showConfirmPassword"
              required
            />

            <v-btn type="submit" color="primary" :disabled="!valid" block>Enviar solicitud</v-btn>
          </v-form>

          <div class="text-center mt-4 d-flex flex-column">
            <span>¿Ya tienes cuenta?</span>
            <RouterLink to="/login" class="register-link">
              Inicia sesión
            </RouterLink>
          </div>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from 'vue'
import { createUserWithEmailAndPassword } from 'firebase/auth'
import { auth } from '@/firebase'
import { registerNegocio } from '@/services/authService'
import { useRouter } from 'vue-router'
import CategoriaService from '@/services/CategoriaService'

const router = useRouter()
const business = ref({})
const password = ref('')
const confirmPassword = ref('')
const showPassword = ref(false)
const showConfirmPassword = ref(false)
const valid = ref(false)
const categorias = ref([])

const emailRules = [
  v => !!v || 'El correo es requerido',
  v => /.+@.+\..+/.test(v) || 'Correo inválido',
]
const passwordRules = [
  v => !!v || 'La contraseña es requerida',
  v => v.length >= 6 || 'Mínimo 6 caracteres',
]
const confirmPasswordRule = [
  v => v === password.value || 'Las contraseñas deben coincidir',
]

const submitForm = async () => {
  if (!valid.value) return
  try {
    const cred = await createUserWithEmailAndPassword(auth, business.value.email, password.value)
    const firebaseUser = cred.user

    await registerNegocio(business.value, firebaseUser.uid, password.value)

    router.push('/login') 
  } catch (error) {
    console.error('Error al registrar empresa:', error)
  }
}

onMounted(()=>{
  CategoriaService.getCategorias().then(({data})=>{
    categorias.value = data 
  })
})
</script>

<style scoped>
.register-link {
  color: #1976d2;
  text-decoration: none;
  font-weight: 500;
}
.register-link:hover {
  text-decoration: underline;
}
</style>
