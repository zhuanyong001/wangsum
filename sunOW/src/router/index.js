import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta:{ requiresAuth: false },
  },
  // {
  //   path: '/about',
  //   name: 'about',
  //   // route level code-splitting
  //   // this generates a separate chunk (about.[hash].js) for this route
  //   // which is lazy-loaded when the route is visited.
  //   component: () => import('@/views/AboutView.vue')
  // },
  // {
  //   path: '/collaboration',
  //   name: 'collaboration',
  //   component: () => import('@/views/collaborationView.vue')
  // },
  // {
  //   path: '/collaborationDetail/:id(\\d+)',
  //   name: 'collaborationDetail',
  //   component: () => import('@/views/collaborationDetail.vue'),
  //   props:true,
  //   beforeEnter: (to, from,next) => {
  //     window.scrollTo(0, 0);
  //     next();
  //   },
  // },
  { path: '/:pathMatch(.*)', name: 'NotFound', component: () => import('@/views/error/404.vue') },
]

const router = createRouter({
  history: createWebHashHistory(),
  // base: '/ny',
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (to.name==='collaborationDetail') {
      return { top: 0 }
    } 
    else{
      return {}
    }
  }
})  



export default router
