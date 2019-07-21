import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    city: localStorage.city || '',
  },
  getters: {},
  mutations: {
    SET_CITY (state, city) {
      state.city = city
    }
  },
  actions: {
    changeCity ({ commit }, city) {
      commit('SET_CITY', city)
    },
  }
})
