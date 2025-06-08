<template>
  <v-app>
    <Header  v-if="showHeader"/>
    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>

<script setup>
  import Header from '@/components/Header.vue';
  import { useRoute } from 'vue-router'
  import { computed } from 'vue'

  const route = useRoute()

  const hideOnRoutes = ['/login', '/register', '/register-empresa']
  const showHeader = computed(() => !hideOnRoutes.includes(route.path))
</script>
