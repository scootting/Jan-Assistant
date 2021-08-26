import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

// Pages
import NotFound from './views/NotFound'
import Login from './views/Login'
import Logout from './views/Logout'
import Dashboard from './views/Dashboard'
import Assets from './views/FixedAssets'
import AddNotDocument from './views/clients/AddNotDocument'

//certificado de diplomados
//import AddGraduateCertificate from './views/document/AddGraduateCertificate'

import Welcome from './views/Welcome'
import Home from './views/Home'
import Layout from './views/Layout'

//bienes e inventarios

//clientes 
//import LoginClient from './views/clients/Login'
//import DashboardClient from './views/clients/Dashboard'
//import { component } from 'vue/types/umd'

// Routes
const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'is-active',
    routes: [
        /*
        {
            path: '/',
            name: 'login',
            component: Home,
            children: [
                { path: '', name: 'layout', component: Layout },
            ],
        },*/
        {
            path: '/', //path: '/login',
            name: 'login',
            component: Login,
        },
        /*
        {
            path: '/client', name: 'loginclient', component: LoginClient
        },*/
        /*
        {
            path: '/client/:id', name: 'dashboardclient', component: DashboardClient,
            children: [
                { path: '', name: 'welcome2', component: Welcome },
                { path: 'nodebt', name: 'addnotdocument2', component: AddNotDocument },
            ],

        },*/
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
                // UserHome will be rendered inside User's <router-view>
                // when /user/:id is matched0
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