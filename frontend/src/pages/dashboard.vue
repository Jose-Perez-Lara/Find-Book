<template>
  <v-layout>
    <v-navigation-drawer
      v-model="drawer"
      :permanent="!mobile"
      :temporary="mobile"
      :rail="!mobile"
      expand-on-hover
      app
    >
      <v-list density="compact" nav>
        <v-list-item
          v-if="authStore.isNegocio"
          @click="setActiveView('services')"
          prepend-icon="mdi-folder"
          title="Servicios"
        />
        <v-list-item
          @click="setActiveView('appointments')"
          prepend-icon="mdi-calendar"
          title="Citas"
        />
        <v-list-item
          @click="setActiveView('profile')"
          prepend-icon="mdi-account"
          title="Perfil"
        />
      </v-list>
    </v-navigation-drawer>

    <v-main height="90vh">
      <v-app-bar flat color="transparent" elevation="0">
        <v-app-bar-nav-icon v-if="mobile" @click="drawer = !drawer" />
      </v-app-bar>

      <v-container fluid>
        <component :is="activeView" />
      </v-container>
    </v-main>
  </v-layout>
</template>

<script setup>
  import { useAuthStore } from '@/stores/auth';
  import ServiceList from '@/components/ServiceList.vue';
  import CitasList from '@/components/CitasList.vue';
  import UserProfile from '@/components/UserProfile.vue';
  import { ref, markRaw, computed } from 'vue';
  import { useDisplay } from 'vuetify';

  const drawer = ref(true);
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

    if (mobile.value) drawer.value = false;
  }

  const authStore = useAuthStore();
  const { mdAndDown } = useDisplay();
  const mobile = computed(() => mdAndDown.value);

</script>
