<template>
  <v-card>
    <v-card-title>Perfil de {{ profile.name }}</v-card-title>
    <v-card-text>
        {{ profile.email }}
    </v-card-text>
  </v-card>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';

const profile = ref({})
const authStore = useAuthStore()

onMounted(()=>{
    profile.value = authStore.user
})

</script>
