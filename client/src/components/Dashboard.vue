<template>
  <table>
    <tr>
      <th>ID</th>
      <th>Source</th>
      <th>Destination</th>
    </tr>
    <template v-for="phrase in phrases">
      <tr v-bind:key="phrase.id">
        <td>{{ phrase.id }}</td>
        <td>{{ phrase.source }}</td>
        <td>{{ phrase.translation }}</td>
      </tr>
    </template>
  </table>
</template>

<script>
  import axios from 'axios'
  export default {
    data() {
      return {
        phrases: []
      }
    },
    async created () {
      const response = await axios.get('http://localhost:8000/phrases')
      this.phrases = response.data;
    }
  }
</script>