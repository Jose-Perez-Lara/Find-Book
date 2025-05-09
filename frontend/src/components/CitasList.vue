<template>
  <v-card>
    <v-card-title>Lista de Citas</v-card-title>
    <v-card-text>
      <v-list>
        <v-list-item-group v-for="cita in citas" :key="cita.id">
          <v-list-item>
            <v-list-item-content>
              <v-list-item-title>{{ cita.title }}</v-list-item-title>
              <v-list-item-subtitle>{{ cita.date }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-card-text>
  </v-card>
</template>

<script setup>
import { ref } from 'vue';

const citas = ref([
  { id: 1, title: 'Cita 1', date: '2025-05-10' },
  { id: 2, title: 'Cita 2', date: '2025-05-11' },
]);
</script>
