<template>
  <v-container fluid class="fill-height d-flex align-center justify-center" style="background-color: #f5f5f5;">
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

          <v-card-title class="text-h3 mb-4 ">Iniciar sesión</v-card-title>

          <v-form ref="form" v-model="valid" lazy-validation>
            <v-text-field
              v-model="email"
              label="Correo electrónico"
              :rules="emailRules"
              prepend-inner-icon="mdi-email"
              required
            ></v-text-field>

            <v-text-field
              v-model="password"
              label="Contraseña"
              :rules="passwordRules"
              prepend-inner-icon="mdi-lock"
              :type="showPassword ? 'text' : 'password'"
              :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
              @click:append="showPassword = !showPassword"
              required
            ></v-text-field>

            <v-btn
              class="mt-4"
              color="primary"
              block
              @click="submit"
              :disabled="!valid || loading"
              :loading="loading"
            >
              Iniciar sesión
            </v-btn>

            <div class="text-center mt-4 d-flex flex-column">
              <span>¿No tienes una cuenta?</span>
              <RouterLink to="/register" class="register-link" style="color: #1976d2;">
                Regístrate
              </RouterLink>
            </div>
          </v-form>
        </v-card>

        <v-snackbar v-model="errorSnackbar" color="error" top>
          {{ errorMessage }}
          <template v-slot:action>
            <v-btn text @click="errorSnackbar = false">Cerrar</v-btn>
          </template>
        </v-snackbar>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()

const email = ref('')
const password = ref('')
const showPassword = ref(false)
const valid = ref(false)
const loading = ref(false)

const errorSnackbar = ref(false)
const errorMessage = ref('')

const emailRules = [
  v => !!v || 'El correo es requerido',
  v => /.+@.+\..+/.test(v) || 'Correo inválido',
]

const passwordRules = [
  v => !!v || 'La contraseña es requerida',
  v => v.length >= 6 || 'Mínimo 6 caracteres',
]

const submit = async () => {
  if (!valid.value) return

  loading.value = true
  try {
    await auth.login(email.value, password.value)
    router.push('/dashboard')
  } catch (error) {
    errorMessage.value = 'Error al iniciar sesión. Verifica tus credenciales.'
    errorSnackbar.value = true
    console.error('Login error:', error)
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.register-link {
  color: #1976d2;
  text-decoration: none;
  font-weight: 500;
  transition: text-decoration 0.2s;
}

.register-link:hover {
  text-decoration: underline;
}
</style>
