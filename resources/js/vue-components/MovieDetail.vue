<template>
  <div class="fixed h-max-full inset-0 z-40" >
    <div class="bg-white h-full overflow-y-scroll">
      <!-- Header -->
      <div class="py-3 px-3 bg-gray-400 text-gray-900 flex">
        <h2 class="mr-auto">{{ activeMovie.name }}</h2>
        <button class="ml-auto text-lg leading-none bg-red-700 text-white shadow-lg rounded-full h-6 w-6 focus:outline-none focus:shadow-outline"
          @click="$emit('close')">
          &times;
        </button>
      </div>
      <!-- Body -->
      <div class="py-3 px-3">
        <div class="flex">
          <img :src="activeMovie.properties.image" class="w-1/2 mr-8 mb-2" alt="poster">
          <div>
            <h2>Hartono</h2>
          </div>
        </div>
        <hr>
        <h3 class="hidden">Showtimes</h3>
        <div class="block">
          <label :for="'date-' + i" v-for="i in [0,1,2,3,4]" class="px-2 py-1 mr-1 mb-1 border inline-block hover:bg-gray-200" :class="{'bg-blue-500': date == moment().add(i, 'days').format('YYYY-MM-DD')}">
            <input class="hidden" type="radio" v-model="date" :id="'date-' + i" :value="moment().add(i, 'days').format('YYYY-MM-DD')">
            {{ moment().add(i, 'days').format('ddd, D MMM') }}
          </label>
        </div>
        <div v-for="city in Object.keys(showtimes)">
          <h3 class="font-bold">{{ city }}</h3>
          <ul>
            <li v-for="cinema in Object.keys(showtimes[city])">
              {{ cinema }}
              <ul class="flex flex-wrap">
                <li v-for="showtime in showtimes[city][cinema]" class="bg-blue-200 px-2 py-1 rounded m-1">
                  {{ showtime.showtime | clock }}
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { stringify as parseParams } from 'qs'
import * as moment from 'moment'

const groupBy = function (xs, key) {
  return xs.reduce(function (rv, x) {
    (rv[x[key]] = rv[x[key]] || []).push(x);
    return rv;
  }, {});
}

export default {
  props: ['activeMovie'],
  data () {
    return {
      showtimes: [],
      date: moment().format('YYYY-MM-DD')
    }
  },
  computed: {
    params () {
      return {
        city: this.city,
        movie: this.activeMovie.id,
        date: this.date
      }
    },
    ...mapState({
      city: state => state.city
    })
  },
  watch: {
    date (value) {
      this.getShowtimes()
    }
  },
  filters: {
    clock (stringdate, format = 'HH:mm') {
      return moment(stringdate).format(format)
    }
  },
  methods: {
    moment () {
      return moment()
    },
    getShowtimes () {
      axios.get('/api/showtimes?' + parseParams(_.pickBy(this.params)))
      .then((response) => {
        const showtimesGrouped = groupBy(response.data.data, 'city')
        Object.keys(showtimesGrouped).forEach(function (city) {
          showtimesGrouped[city] = groupBy(showtimesGrouped[city], 'cinema_name')
        })
        this.showtimes = showtimesGrouped
      });
    }
  },
  mounted () {
    this.getShowtimes()
  },
}
</script>

<style>

</style>
