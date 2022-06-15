import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

// Pages
import NotFound from './views/NotFound'
import Login from './views/Login'
import Logout from './views/Logout'
import Dashboard from './views/Dashboard'
import NoCertificate from './views/clients/AddNotDocument'
import borrador from './views/borrador'
import NuevaConvocatoria from './views/NewCall'
import ValidarSolicitud from './views/ValidateRequest'
import CursosPostgrado from './views/ClassPost'

//certificado de diplomados
//import AddGraduateCertificate from './views/document/AddGraduateCertificate'

import Welcome from './views/Welcome'
import Home from './views/Home'
import Layout from './views/Layout'

// Routes
const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'is-active',
    routes: [
        {
            path: '/', //path: '/login',
            name: 'home',
            component: Home,
        },
        {
            path: '/login', //path: '/login',
            name: 'login',
            component: Login,
        },
        {
            path: '/logout',
            name: 'logout',
            component: Logout,
            meta: {
                requiresAuth: true,
            }
        },
        {
            path: '/api', // /dashboard  /api/assets /api/profiles
            name: 'dashboard',
            component: Dashboard,
            children: [
                { path: '', name: 'welcome', component: Welcome },
                { path: '/nocertificate', name: 'nocertificate', component: NoCertificate },
                { path: '/borrador', name: 'borrador', component: borrador },
                { path: '/nuevaConvocatoria', name: 'nuevaConvocatoria', component: NuevaConvocatoria },
                { path: '/validarsolicitud', name: 'validarSolicitud', component: ValidarSolicitud },
                { path: '/cursospostgrado', name: 'cursosPostgrado', component: CursosPostgrado },
            ],
            meta: {
                requiresAuth: true,
            }
        },
        {
            path: '/404',
            name: '404',
            component: NotFound,
        },
        {
            path: '*',
            redirect: '/404',
        },
    ],
});

export default router