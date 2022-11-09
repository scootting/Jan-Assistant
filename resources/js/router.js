import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

// Pages
import NotFound from './views/NotFound'
import Login from './views/Login'
import Logout from './views/Logout'
import Dashboard from './views/Dashboard'
import Information from './views/clients/Information'
import Password from './views/clients/Password'



import NuevaConvocatoria from './views/NewCall'

//certificado de diplomados
//import AddGraduateCertificate from './views/document/AddGraduateCertificate'

import Welcome from './views/Welcome'
import Home from './views/Home'
import Layout from './views/Layout'

//Tesoro: Modulo para seleccionar los valores en linea
import SaleStudents from './views/treasure/SaleStudents'
//Documento: Modulo para gestionar las solicitudes
import Requests from './views/document/Requests'

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
            path: '/api',
            name: 'dashboard',
            component: Dashboard,
            children: [
                { path: '', name: 'welcome', component: Welcome },
                { path: '/information', name: 'information', component: Information },
                { path: '/password', name: 'password', component: Password },
                { path: '/salestudents', name: 'salestudents', component: SaleStudents },
                { path: '/requests', name: 'requests', component: Requests },
 
                { path: '/nuevaConvocatoria', name: 'nuevaConvocatoria', component: NuevaConvocatoria },
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