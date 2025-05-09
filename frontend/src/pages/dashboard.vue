<template>
     <v-navigation-drawer
        expand-on-hover
        rail
      >
       <v-list density="compact" nav>
          <v-list-item v-if="authStore.$state.user.rol_id == 2" @click="setActiveView('services')" prepend-icon="mdi-folder" title="Servicios" />
          <v-list-item @click="setActiveView('appointments')" prepend-icon="mdi-calendar" title="Citas" />
          <v-list-item @click="setActiveView('profile')" prepend-icon="mdi-account" title="Perfil" />
      </v-list>
    </v-navigation-drawer>
    <v-main>
        <component :is="activeView" />
    </v-main>
    
</template>

<script setup>
  import { useAuthStore } from '@/stores/auth';
  import ServiceList from '@/components/ServiceList.vue';
  import CitasList from '@/components/CitasList.vue';
  import UserProfile from '@/components/UserProfile.vue';

  const activeView = ref(UserProfile);

  function setActiveView(view) {
    switch (view) {
      case 'services':
        activeView.value = ServiceList
        break;
      case 'appointments':
        activeView.value = CitasList
        break;
      case 'profile':
        activeView.value = UserProfile
        break;
      default:
        activeView.value = UserProfile
    }
  }

  const authStore = useAuthStore()
</script>
