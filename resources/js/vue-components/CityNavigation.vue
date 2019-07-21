<template>
  <div>
    <div class="w-auto px-5 pt-5 pb-3 text-sm font-bold uppercase text-gray-500 flex justify-between bg-gray-900 items-center sticky top-0 md:hidden">
      {{ activeCity ? activeCity : 'Select city' }}
      <button @click="toggle" class="md:hidden text-lg leading-none bg-gray-700 text-white rounded-full h-5 w-5">{{ isOpen ? '&times;' : '-' }}</button>
    </div>
    <ul class="list-none md:block" :class="{'block': isOpen, 'hidden': !isOpen}">
        <li v-for="city in cities"
          class="w-auto px-5 py-3 text-sm cursor-pointer text-white rounded-lg"
          :class="{'bg-blue-800 hover:bg-blue-700': activeCity == city, '': activeCity != city }"
          @click="$store.dispatch('changeCity', city)">
          {{ city }}
        </li>
    </ul>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import city from '../api/city'

export default {
  data () {
    return {
      cities: [],
      isOpen: false,
    }
  },
  computed: mapState({
    activeCity: state => state.city
  }),
  methods: {
    toggle () {
      this.isOpen = !this.isOpen
    }
  },
  async mounted () {
    const cities = await city.getAll()
    this.cities = cities
  }
}
</script>

<style>

</style>
