<template>
  <div class="min-h-screen bg-gray-200 w-screen">
    <CityNavigation class="bg-gray-900 px-2 pb-2 w-screen md:w-64 md:fixed md:top-0 md:overflow-y-scroll md:max-h-full z-50" />
    <div class="px-2 md:pl-64 py-8">
      <h1 v-if="selectedCity != ''" class="text-center my-3">
        Movies in {{ selectedCity }}
      </h1>
      <div class="flex flex-wrap justify-center">
        <div class="bg-white w-1/2 md:w-auto" v-for="movie in movies" @click="activeMovie = movie">
          <img :src="movie.properties.image" alt="movie poster">
        </div>
      </div>
    </div>
    <MovieDetail v-if="activeMovie != null" :activeMovie="activeMovie" @close="activeMovie = null"/>
  </div>
</template>

<script>
  import { mapState } from 'vuex'
  import CityNavigation from './CityNavigation'
  import MovieDetail from './MovieDetail'
  import movie from '../api/movie'

  export default {
    data () {
      return {
          movies: [],
          activeMovie: null,
      }
    },
    components: {
      CityNavigation,
      MovieDetail
    },
    computed: mapState({
      selectedCity: state => state.city
    }),
    watch: {
      async selectedCity (city) {
        this.clearActiveMovie()
        await this.getMovies(city ? { city } : {})
      }
    },
    methods: {
      clearActiveMovie () {
        this.activeMovie = null
      },
      async getMovies (params) {
        const movies = await movie.get(params)
        this.movies = movies.data
      }
    },
    async mounted() {
      await this.getMovies({})
    }
  }
</script>

<style>
.blur {
  filter: blur(3px) brightness(0.7);
}
</style>
