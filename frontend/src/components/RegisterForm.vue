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

                <v-card-title class="text-h3 mb-4 ">Registrate</v-card-title>
                <v-form ref="form" v-model="valid" @submit.prevent="submitForm">
                    <v-text-field
                    v-model="user.email"
                    label="Correo electrónico"
                    :rules="emailRules"
                    prepend-inner-icon="mdi-email"
                    required
                    ></v-text-field>
            
                    <v-text-field
                    v-model="user.name"
                    label="Nombre"
                    prepend-inner-icon="mdi-account"
                    required
                    ></v-text-field>
                    <v-text-field
                    v-model="user.telefono"
                    label="Telefono"
                    :counter="9"
                    prepend-inner-icon="mdi-phone"
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
            
                    <v-text-field
                    v-model="confirmPassword"
                    label="Confirmar contraseña"
                    :rules="confirmPasswordRule"
                    prepend-inner-icon="mdi-lock"
                    :type="showConfirmPassword ? 'text' : 'password'"
                    :append-icon="showConfirmPassword ? 'mdi-eye-off' : 'mdi-eye'"
                    @click:append="showConfirmPassword = !showConfirmPassword"
                    required
                    ></v-text-field>
            
                    <v-btn type="submit" :disabled="!valid" color="#347c88"  block >Registrar</v-btn>
                </v-form>

                <div class="text-center mt-4 d-flex flex-column">
                <span>¿Ya tienes cuenta?</span>
                <RouterLink to="/login" class="register-link" style="color: #347c88;">
                    Login
                </RouterLink>
                </div>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
  </template>
  
<script setup>
    import { createUserWithEmailAndPassword } from 'firebase/auth';
    import { auth } from '@/firebase';
    import { ref } from 'vue';
    import { register } from '@/services/authService';
    import { useRouter } from 'vue-router';

    const router = useRouter()
    const form = ref(null)
    const valid = ref(false)
    const user = ref({
        email: '',
        name: '',
        telefono: '',
    })

    const password = ref('')
    const confirmPassword = ref('')
    const showPassword = ref(false)
    const showConfirmPassword = ref(false)

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
            const cred = await createUserWithEmailAndPassword(auth, user.value.email, password.value)
            const firebaseUser = cred.user
            await register(user.value, firebaseUser.uid, password.value)
            router.push('/dashboard')
        } catch (error) {
            console.error('Registro error:', error)
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
  