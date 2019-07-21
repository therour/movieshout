import axios from 'axios'
import qs from 'qs'

export default {
  async get (params) {
    const response = await axios.get('/api/movies?' + qs.stringify(params))
    return response.data
  }
}
