import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import BienvenidaView from '../views/BienvenidaView.vue'
import CumpleView from '../views/CumpleView.vue'

const router = createRouter({
  // Hash en vez de history: GitHub Pages no reescribe rutas, así que con
  // history un F5 en /bienvenida devolvería 404. Con hash las URLs quedan
  // como /grettings-bbdo/#/bienvenida y funcionan siempre.
  history: createWebHashHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'home', component: HomeView, meta: { title: 'BBDO Grettings' } },
    {
      path: '/bienvenida',
      name: 'bienvenida',
      component: BienvenidaView,
      meta: { title: 'Bienvenida BBDO' },
    },
    { path: '/cumple', name: 'cumple', component: CumpleView, meta: { title: 'Cumple BBDO' } },
  ],
})

router.afterEach((to) => {
  if (to.meta?.title) document.title = to.meta.title
})

export default router
