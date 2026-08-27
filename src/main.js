import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

// Bootstrap 5.3.3, la misma versión que cargaba el proyecto original por CDN
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

// Paleta de marca primero: el resto de las hojas la referencia
import './assets/styles/marca.scss'
// Estilos de las tarjetas, portados del proyecto original
import './assets/styles/style.scss'
import './assets/styles/bienvenida.scss'
// Interfaz de la herramienta y marcas del editor
import './assets/styles/ui.scss'
import './assets/styles/editor.scss'

createApp(App).use(router).mount('#app')
