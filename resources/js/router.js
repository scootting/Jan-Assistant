import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

// Pages
import NotFound from './views/NotFound'
import Login from './views/Login'
import Logout from './views/Logout'
import Dashboard from './views/Dashboard'
import Assets from './views/FixedAssets'

//usuario
import Users from './views/application/Users'
import AddUser from './views/application/AddUser'
import EditUser from './views/application/EditUser'
import ShowUser from './views/application/ShowUser'
import EditUserProfiles from './views/application/EditUserProfiles'


//persona
import Persons from './views/application/Persons'
import AddPerson from './views/application/AddPerson'
import EditPerson from './views/application/EditPerson'
import Welcome from './views/Welcome'
import Home from './views/Home'
import Layout from './views/Layout'

//bienes e inventarios
import Inventory from './views/inventory/Inventory'
import Inventory2 from './views/inventory/Inventory2'
import InventoryDetail from './views/inventory/InventoryDetail'
import NewInventory from './views/inventory/NewInventory'
import Formalities from './views/Formalities'
import AddTaxExemption from './views/clients/AddTaxExemption'

//tesoreria
import Solvency from './views/treasure/Solvency'


// Routes
const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'is-active',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
            children: [
                { path: '', name: 'layout', component: Layout },
                { path: '/formalities', name: 'formalities', component: Formalities },
                { path: '/taxExemption', name: 'addTaxExemption', component: AddTaxExemption },
            ],
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
                // when /user/:id is matched0
                { path: '', name: 'welcome', component: Welcome },
                { path: 'assets', name: 'assets', component: Assets },
                // enlaces para administrar los usuarios
                { path: 'users', name: 'users', component: Users },
                { path: 'user/add', name: 'adduser', component: AddUser },
                { path: 'user/:id', name: 'edituser', component: EditUser },
                { path: 'user/show', name: 'showuser', component: ShowUser },
                { path: 'user/profiles', name: 'edituserprofiles', component: EditUserProfiles },

                // enlaces para administrar las personas
                { path: 'persons', name: 'persons', component: Persons },
                { path: 'person/add', name: 'addperson', component: AddPerson },
                { path: 'person/:id', name: 'editperson', component: EditPerson },
                //{ path: 'person/show/:id', name: 'editperson', component: EditPerson },
                //{ path: 'welcome', name: 'welcome', component: Welcome },
                { path: 'inventory', name: 'inventory', component: Inventory },
                { path: 'inventory/:soa', name: 'inventorydetail', component: InventoryDetail },
                { path: 'inventory2', name: 'inventory2', component: Inventory2 },
                { path: 'newinventory', name: 'newinventory', component: NewInventory },

                //enlaces para la administracion de paginas de tesoreria
                { path: 'solvency', name: 'solvency', component: Solvency },
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