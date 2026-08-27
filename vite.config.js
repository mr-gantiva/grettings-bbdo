import vue from '@vitejs/plugin-vue'
import { defineConfig } from 'vite'

// El sitio se publica en https://mr-gantiva.github.io/grettings-bbdo/
// Sin esta base, todas las rutas absolutas apuntarían a la raíz del dominio
// y ni el JS ni las imágenes cargarían.
export default defineConfig({
  base: '/grettings-bbdo/',
  plugins: [vue()],
})
