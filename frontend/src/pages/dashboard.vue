<template>
  <v-main>  
     <v-navigation-drawer
        expand-on-hover
        rail
      >
       <v-list density="compact" nav>
          <v-list-item v-if="authStore.isNegocio" @click="setActiveView('services')" prepend-icon="mdi-folder" title="Servicios" />
          <v-list-item @click="setActiveView('appointments')" prepend-icon="mdi-calendar" title="Citas" />
          <v-list-item @click="setActiveView('profile')" prepend-icon="mdi-account" title="Perfil" />
      </v-list>
    </v-navigation-drawer>
        <component :is="activeView" />
    </v-main>
    
</template>

<script setup>
  import { useAuthStore } from '@/stores/auth';
  import ServiceList from '@/components/ServiceList.vue';
  import CitasList from '@/components/CitasList.vue';
  import UserProfile from '@/components/UserProfile.vue';
  import { ref, onBeforeMount, markRaw } from 'vue';

  const activeView = ref(markRaw(UserProfile));

  function setActiveView(view) {
    switch (view) {
      case 'services':
        activeView.value = markRaw(ServiceList);
        break;
      case 'appointments':
        activeView.value = markRaw(CitasList);
        break;
      case 'profile':
        activeView.value = markRaw(UserProfile);
        break;
      default:
        activeView.value = markRaw(UserProfile);
    }
  }

  const authStore = useAuthStore()

  onBeforeMount( async ()=>{
    await authStore.initialize()
  })
</script>
