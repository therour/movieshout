import axios from 'axios'

export default {
  async getAll () {
    const response = await axios.get('/api/cities')
    return response.data
  }
}
