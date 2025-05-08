import axios from 'axios'

axios.defaults.baseURL = 'http://localhost:8000/';
axios.defaults.withCredentials = true;

const token = localStorage.getItem('token')
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

export default {
    install(app) {
        app.config.globalProperties.$axios = axios
    }
}