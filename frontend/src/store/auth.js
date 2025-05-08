import {
    defineStore
} from 'pinia';
import axios from 'axios';


export const userAuthStore = defineStore('auth', {
    state: () => ({
        user: {},
        token: localStorage.getItem('token') || null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
    },

    actions: {
        async login(credentials, router) {
            try {
                await axios.get('/sanctum/csrf-cookie');
                const response = await axios.post('http://localhost:8000/api/v1/login', credentials);

                this.token = response.data.token;
                localStorage.setItem('token', this.token);

                this.user = response.data.user;
                // console.log('current response token', this.user);
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;

                router.push('/dashboard');

            } catch (error) {
                console.error('Connexion échouée:', error.response + ' ' + error.message);
                throw error;
            }
        },

        logout(router) {
            this.token = null;
            this.user = null;
            localStorage.removeItem('token');
            delete axios.defaults.headers.common['Authorization'];
            router.push('/auth/login');
        },

        async fetchUser() {
            if (!this.token) return;
            try {
                const response = await axios.get('http://localhost:8000/api/v1/user');
                this.user = response.data;
            } catch (error) {
                this.logout();
            }
        },
    },

    persist: true,
});