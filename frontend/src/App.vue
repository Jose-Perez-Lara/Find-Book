<template>
  <v-app>
    <router-view />
  </v-app>
</template>

<script setup>

  import { onMounted,  } from 'vue';
  import { getAuth, onAuthStateChanged } from 'firebase/auth';
  import { useAuthStore } from './stores/auth';
  let auth
  const authStore = useAuthStore()

  onMounted(()=>{
    auth = getAuth()
    onAuthStateChanged(auth, (user) =>{
      if(user){
        authStore.initialize(user.accessToken)
      }
    })
  })

</script>
