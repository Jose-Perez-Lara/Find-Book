<template>
  <v-container fluid class="pa-0" style="background-color: #f7fafb; min-height: 100vh;">
    <v-container class="hero-section pa-12 mb-12" rounded="lg" elevation="6">
      <v-row align="center" justify="center" class="text-center">
        <v-col cols="12" md="8" lg="6">
          <h1 class="display-2 font-weight-bold" style="color: #2c6b74;">Encuentra el mejor servicio para ti</h1>
          <p class="subtitle-1 mb-6" style="color: #557c85;">Explora negocios</p>
          <v-text-field
            v-model="search"
            label="Buscar negocios o servicios"
            append-inner-icon="mdi-magnify"
            solo
            hide-details
            color="#347c88"
            style="max-width: 500px; margin: 0 auto;"
          />
        </v-col>
      </v-row>
    </v-container>

    <v-container class="mx-auto" style="max-width: 1500px;">
      <h2 class="text-h5 mb-6" style="color:#347c88; font-weight: 600;">Categorías Populares</h2>
      <v-row dense justify="center">
        <v-col
          v-for="categoria in categoriasFiltradas"
          :key="categoria.id"
          cols="12" sm="6" md="4" lg="4"
          class="d-flex py-4"
        >
          <v-card
            class="category-card elevation-2 pa-6 rounded-lg hover-scale d-flex flex-column justify-center align-center"
            :class="{ 'active-category': categoriasSeleccionadas.includes(categoria.id) }"
            @click="toggleCategoria(categoria.id)"
          >
            <v-icon :color="categoriasSeleccionadas.includes(categoria.id) ? 'white' : '#347c88'" size="40" class="mb-4">
              mdi-tag
            </v-icon>
            <div
              class="font-weight-bold text-h5 mb-2"
              :style="{ color: categoriasSeleccionadas.includes(categoria.id) ? 'white' : '#2c6b74' }"
            >
              {{ categoria.nombre }}
            </div>
            <div :style="{ color: categoriasSeleccionadas.includes(categoria.id) ? '#e1f0f2' : '#5a7a81' }">
              {{ categoria.descripcion || 'Explora negocios en esta categoría' }}
            </div>
          </v-card>
        </v-col>
      </v-row>
    </v-container>

    <v-container class="py-8" style="background-color: #eaf2f3; border-radius: 1rem;">
      <h2 class="text-h5 mb-6" style="color:#347c88; font-weight: 600;">Negocios Destacados</h2>
      <v-row dense>
        <v-col
          v-for="negocio in negociosFiltrados"
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
              <v-btn color="#347c88" text @click="verNegocio(negocio.id)" class="font-weight-medium">
                Ver más
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </v-container>
</template>

<script setup>
  import { ref, computed, onMounted } from 'vue'
  import CategoriaService from '@/services/CategoriaService'
  import NegocioService from '@/services/NegocioService'
  import { useRouter } from 'vue-router'

  const categorias = ref([])
  const negocios = ref([])
  const search = ref('')
  const categoriasSeleccionadas = ref([])
  const router = useRouter()

  onMounted(async () => {
    const { data: cats } = await CategoriaService.getCategorias()
    categorias.value = cats[0] || []

    const { data: negs } = await NegocioService.getAllNegocios()
    negocios.value = negs || []
  })

  function toggleCategoria(id) {
    if (categoriasSeleccionadas.value.includes(id)) {
      categoriasSeleccionadas.value = categoriasSeleccionadas.value.filter(c => c !== id)
    } else {
      categoriasSeleccionadas.value.push(id)
    }
  }

  const categoriasFiltradas = computed(() => {
    if (!search.value) return categorias.value
    return categorias.value.filter(categoria =>
      categoria.nombre.toLowerCase().includes(search.value.toLowerCase()) ||
      (categoria.descripcion && categoria.descripcion.toLowerCase().includes(search.value.toLowerCase()))
    )
  })

  const negociosFiltrados = computed(() => {
    let filtrados = negocios.value

    if (search.value) {
      filtrados = filtrados.filter(negocio =>
        negocio.nombre.toLowerCase().includes(search.value.toLowerCase()) ||
        (negocio.descripcion && negocio.descripcion.toLowerCase().includes(search.value.toLowerCase())) ||
        (negocio.categoria?.nombre && negocio.categoria.nombre.toLowerCase().includes(search.value.toLowerCase()))
      )
    }

    if (categoriasSeleccionadas.value.length > 0) {
      filtrados = filtrados.filter(negocio =>
        categoriasSeleccionadas.value.includes(negocio.categoria?.id)
      )
    }

    return filtrados
  })

  function verNegocio(id) {
    router.push(`/negocios/${id}`)
  }

</script>

<style scoped>
  .hero-section {
    background-color: #d9edf1;
    border-radius: 1rem;
    box-shadow: 0 8px 24px rgba(52, 124, 136, 0.15);
  }

  .category-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
    height: 100%;
  }
  .category-card:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 28px rgba(52, 124, 136, 0.25);
  }
  .active-category {
    background-color: #347c88 !important;
    color: white !important;
    box-shadow: 0 12px 28px rgba(52, 124, 136, 0.35) !important;
    border: 2px solid #2c6b74;
  }

  .business-card {
    transition: box-shadow 0.3s ease;
  }
  .business-card:hover {
    box-shadow: 0 12px 32px rgba(52, 124, 136, 0.3);
  }

  .hover-scale {
    cursor: pointer;
    transition: transform 0.3s ease;
  }
  .hover-scale:hover {
    transform: scale(1.05);
  }
</style>
