<template>
  <v-card>
    <v-card-title>Lista de Servicios</v-card-title>
    <v-card-text>
      <v-list>
        <v-list-item-group v-for="service in services" :key="service.id">
          <v-list-item>
            <v-list-item-content>
              <v-list-item-title>{{ service.name }}</v-list-item-title>
              <v-list-item-subtitle>{{ service.description }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-card-text>
  </v-card>
</template>

<script setup>
import { ref } from 'vue';

const services = ref([
  { id: 1, name: 'Servicio 1', description: 'Descripción del servicio 1' },
  { id: 2, name: 'Servicio 2', description: 'Descripción del servicio 2' },
]);

</script>
