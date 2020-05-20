import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

// Pages
import NotFound from './views/NotFound'
import Login from './views/Login'
import Logout from './views/Logout'
import Dashboard from './views/Dashboard'
import Assets from './views/FixedAssets'
import Users from './views/Users'
import Persons from './views/Persons'
import AddPerson from './views/AddPerson'
import Welcome from './views/Welcome'
import DeliveryDocuments from './views/DeliveryDocuments'
import Home from './views/Home'
import Inventory from './views/Inventory'
import EditDeliveryDocument from './views/EditDeliveryDocument'

// Routes
const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'is-active',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
        },
        {
            path: '/login',
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
                // UserHome will be rendered inside User's <router-view>
                // when /user/:id is matched
            { path: '', name: 'welcome', component: Welcome },
            { path: 'assets', name: 'assets', component: Assets },
            { path: 'deliverydocuments', name: 'deliverydocuments', component: DeliveryDocuments }, //deliverydocument
            { path: 'users', name: 'users', component: Users },
            { path: 'persons', name: 'persons', component: Persons },
            { path: 'persons/add', name: 'addperson', component: AddPerson },
            { path: 'welcome', name: 'welcome', component: Welcome },
            { path: 'inventory', name: 'inventory', component: Inventory },
            { path: 'editdeliverydocument', name: 'editdeliverydocument', component: EditDeliveryDocument}
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