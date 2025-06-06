<template>
    <v-app-bar app color="teal-darken-4">
        <template v-slot:image>
          <v-img
            gradient="to top right, rgba(19,84,122,.8), rgba(128,208,199,.8)"
          ></v-img>
        </template>
         <v-toolbar-title class="d-flex align-center">
            <v-btn
                to="/"
                elevation="0"
            >
                <v-img
                src="../assets/logo_white.png"
                alt="Logo"
                width="25%"
                height="25%"
                cover
                />
                <span class="white--text font-weight-medium ml-2">Find&Book</span>
            </v-btn>
        </v-toolbar-title>

        <v-btn text to="/explorar" class="mx-2">Explorar</v-btn>
        <template v-if="authStore.isAuthenticated">
            <v-btn to="/reservas" class="mx-2">Mis Reservas</v-btn>
            <v-btn v-if="!authStore.isNegocio" text to="/favoritos" class="mx-2">Negocios Favoritos</v-btn>
            <v-btn v-if="authStore.isNegocio" text to="/servicios" class="mx-2">Mis servicios</v-btn>

            <v-btn icon="mdi-account" to="/dashboard"/>
            <v-btn icon="mdi-logout" @click="logout"/>
        </template>

        <template v-else>
        <v-btn text to="/login">Login</v-btn>
        <v-btn text to="/register">Registrarse</v-btn>
        </template>
        
    </v-app-bar>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

const router = useRouter()

const logout = () => {
    authStore.logout()
    router.push('/login')
}
</script>

<style scoped>
.text-decoration-none {
  text-decoration: none;
}
</style>
