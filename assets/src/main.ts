import {createApp} from 'vue'
import {createRouter, createWebHashHistory} from 'vue-router'
import Toast, {PluginOptions, POSITION} from 'vue-toastification'

import './scss/index.scss'
import App from './app.vue'
// @ts-ignore
import routes from '~pages'

const toastOptions: PluginOptions = {
  position: POSITION.TOP_RIGHT,
  maxToasts: 5,
  timeout: 5000,
  pauseOnHover: true,
  pauseOnFocusLoss: true,
  closeButton: false,
  closeOnClick: false,
  draggable: true,
  transition: 'Vue-Toastification__slideBlurred',
}

const router = createRouter({
  history: createWebHashHistory(),
  routes,
})
const app = createApp(App)
app.use(router)
app.use(Toast, toastOptions)

document.addEventListener('DOMContentLoaded', () => {
  app.mount('#app-root')
})
