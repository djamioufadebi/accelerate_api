import {
    createApp
} from 'vue'
import App from './App.vue';
import './registerServiceWorker';
import router from './router';
import store from './store';
import axios from './plugins/axios';
import {
    createPinia
} from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';


// Library Components
import VueSweetalert2 from 'vue-sweetalert2'
import VueApexCharts from 'vue3-apexcharts'
import BootstrapVue3 from 'bootstrap-vue-3'
import CounterUp from 'vue3-autocounter'
import 'aos/dist/aos.css'

// Custom Components & Directives
import globalComponent from './plugins/global-components'
import globalDirective from './plugins/global-directive'
import globalMixin from './plugins/global-mixin'

// import font awesome
import '@fortawesome/fontawesome-free/css/all.min.css';


require('waypoints/lib/noframework.waypoints.min')

const app = createApp(App)
const pinia = createPinia()
pinia.use(piniaPluginPersistedstate);
app.use(store).use(router).use(axios).use(pinia)

// Library Components
app.use(VueSweetalert2)
app.use(VueApexCharts)
app.use(BootstrapVue3)
app.component('counter-up', CounterUp)

// Custom Components & Directives
app.use(globalComponent)
app.use(globalDirective)
app.mixin(globalMixin)

app.mount('#app')



export default app