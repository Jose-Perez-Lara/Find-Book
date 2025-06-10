<template>
    <v-container class="py-8" style="background-color: #eaf2f3; border-radius: 1rem;">
      <h2 class="text-h5 mb-6" style="color:#347c88; font-weight: 600;">Tus negocios favoritos</h2>
      <v-row dense>
        <v-col
          v-for="negocio in negociosFavoritos"
          :key="negocio.id"
          cols="12" sm="6" md="4"
          class="d-flex"
        >
          <v-card class="business-card rounded-lg elevation-3 d-flex flex-column">
            <v-img
              v-if="negocio.imagen_portada"
              :src="'http://localhost:8000/'+negocio.imagen_portada"
              height="180px"
              class="rounded-t-lg"
            />
            <div v-else class="d-flex align-center justify-center" style="height: 180px; background-color: #cadfe1; color: #4f6e76; font-weight: bold;">
              Sin imagen
            </div>
            <v-card-title class="text-h6" style="color: #2c6b74;">{{ negocio.nombre }}</v-card-title>
            <v-card-subtitle style="color: #4f6e76;">{{ negocio.categoria?.nombre }}</v-card-subtitle>
            <v-card-text class="flex-grow-1" style="color: #556f75;">
              {{ negocio.descripcion?.slice(0, 100) || 'Descripción no disponible' }}
            </v-card-text>
            <v-card-actions>
              <v-spacer />
              <v-btn @click="selectFavorito(negocio.id)" color="red" :icon="esFavorito(negocio.id) ? 'mdi-heart' : 'mdi-heart-outline'"></v-btn>
              <v-btn color="#347c88" text @click="verNegocio(negocio.id)" class="font-weight-medium">
                Ver más
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
</template>
<script setup>
    import { ref, onMounted } from 'vue';
    import NegocioService from '@/services/NegocioService';
    import FavoritoService from '@/services/FavoritoService';
    import { useAuthStore } from '@/stores/auth'
    import { useRouter } from 'vue-router'
    
    const router = useRouter()
        
    const favoritosUser = ref([])
    const authStore = useAuthStore()
    const negociosFavoritos = ref([])

    onMounted(async () => {
        const { data: negocios } = await NegocioService.getNegociosFavoritos(authStore.user.id)
        negociosFavoritos.value = negocios

        const { data: favoritos } = await FavoritoService.getFavoritos(authStore.user.id)
        favoritosUser.value = favoritos
    })

    const esFavorito = (negocioId) => {
        const favorito = favoritosUser.value.find(favorito => favorito.negocio_id = negocioId)
        return favorito != undefined
    }

    const selectFavorito = async (negocioId) => {
        await FavoritoService.toggleFavorito(negocioId, authStore.user.id)
        const { data: favoritos } = await FavoritoService.getFavoritos(authStore.user.id)
        favoritosUser.value = favoritos
    }

    function verNegocio(id) {
        router.push(`/negocios/${id}`)
    }
</script>