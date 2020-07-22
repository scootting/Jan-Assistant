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
import EditPerson from './views/EditPerson'
import Welcome from './views/Welcome'
import DeliveryDocuments from './views/DeliveryDocuments'
import EditDeliveryDocument from './views/EditDeliveryDocument'
import AddDeliveryDocument from './views/AddDeliveryDocument'
import Home from './views/Home'
import Inventory from './views/Inventory'
import DonationDocuments from './views/DonationDocuments'
import EditDonationDocument from './views/EditDonationDocument'
import AddDonationDocument from './views/AddDonationDocument'
import CarpentryDocuments from './views/CarpentryDocuments'
import EditCarpentryDocument from './views/EditCarpentryDocument'
import AddCarpentryDocument from './views/AddCarpentryDocument'
import Layout from './views/Layout'
import Formalities from './views/Formalities'

//clients
import AddTaxExemption from './views/clients/AddTaxExemption'

// Routes
const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'is-active',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
            children:[
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
            { path: 'deliverydocuments', name: 'deliverydocuments', component: DeliveryDocuments }, //aqui inicia compra
            { path: 'editdeliverydocument', name: 'editdeliverydocument', component: EditDeliveryDocument},
            { path: 'deliverydocument/add', name: 'adddeliverydocument', component: AddDeliveryDocument},
            { path: 'users', name: 'users', component: Users },
            { path: 'persons', name: 'persons', component: Persons },
            { path: 'person/add', name: 'addperson', component: AddPerson },
            { path: 'person/:id', name: 'editperson', component: EditPerson },
            //{ path: 'person/show/:id', name: 'editperson', component: EditPerson },
            //{ path: 'welcome', name: 'welcome', component: Welcome },
            { path: 'inventory', name: 'inventory', component: Inventory },
            { path: 'donationdocuments', name: 'donationdocuments', component: DonationDocuments }, //Aqui inicia donacion
            { path: 'editdonationdocument', name: 'editdonationdocument', component: EditDonationDocument},
            { path: 'donationdocument/add', name: 'adddonationdocument', component: AddDonationDocument},
            { path: 'carpentrydocuments', name: 'carpentrydocuments', component: CarpentryDocuments },
            { path: 'editcarpentrydocument', name: 'editcarpentrydocument', component: EditCarpentryDocument},
            { path: 'carpentrydocument/add', name: 'addcarpentrydocument', component: AddCarpentryDocument}
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