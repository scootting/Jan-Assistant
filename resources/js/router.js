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
import EditInventory2 from './views/inventory/EditInventory2'
import InventoryDetail from './views/inventory/InventoryDetail'
import NewInventory from './views/inventory/NewInventory'
import Inventory2Detail from './views/inventory/Inventory2Detail'
import Formalities from './views/Formalities'
import AddTaxExemption from './views/clients/AddTaxExemption'
import Active from './views/inventory/Active'
import EditActive from './views/inventory/EditActive'
import QrPrint from './views/inventory/QrPrint'

//activos fijos
import DocumentsFixedAssets from './views/fixedasset/DocumentsFixedAssets'
import SelectedFixedAssetsByDocument from './views/fixedasset/SelectedFixedAssetsByDocument'

//tesoreria
import Solvency from './views/treasure/Solvency'
import SaleStudents from './views/treasure/SaleStudents'    //lista de dias de alumnos nuevos
import Students from './views/treasure/Students'            //alumnos nuevos
import AppendDebtors from './views/treasure/AppendDebtors'  //lista de dias de deudores
import Debtors from './views/treasure/Debtors'              //deudores
import HistoryTransactions from './views/treasure/HistoryTransactions'              //historial de transacciones

//clientes 
import LoginClient from './views/clients/Login'
import DashboardClient from './views/clients/Dashboard'

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
                { path: '/addNotDocument', name: 'addnotdocument', component: AddNotDocument },
            ],
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
        },
        {
            path: '/client', name: 'loginclient', component: LoginClient
        },
        {
            path: '/client/:id', name: 'dashboardclient', component: DashboardClient,
            children: [
                { path: '', name: 'welcome2', component: Welcome },
                { path: 'nodebt', name: 'addnotdocument2', component: AddNotDocument },
            ],

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
                { path: 'inventory2/:id', name: 'editinventory2', component: EditInventory2 },
                { path: 'newinventory', name: 'newinventory', component: NewInventory },
                { path: 'inventory2detail/:no_cod', name: 'inventory2detail', component: Inventory2Detail },
                { path: 'active', name: 'active', component: Active },
                { path: 'active/:id', name: 'editactive', component: EditActive },

                //enlaces para la administracion de paginas de tesoreria
                { path: 'solvency', name: 'solvency', component: Solvency }, // solvencias
                { path: 'salestudents', name: 'salestudents', component: SaleStudents }, //dia de alumnos nuevos
                { path: 'salestudents/:id', name: 'students', component: Students }, //alumnos nuevos
                { path: 'appenddebtors', name: 'appenddebtors', component: AppendDebtors }, //dia de deudores
                { path: 'appenddebtors/:id', name: 'debtors', component: Debtors }, // deudores
                { path: 'documentsfixedassets', name: 'documentsfixedassets', component: DocumentsFixedAssets }, //lista de documentos de entrega activos fijos
                { path: 'documentsfixedassets/:id', name: 'selectedFixedAssetsByDocument', component: SelectedFixedAssetsByDocument }, // documentos de entrega activos fijos impresion
                { path: 'historytransactions', name: 'historytransactions', component: HistoryTransactions }, // historial de transacciones por persona
            ],
            meta: {
                requiresAuth: true,
            }
        },
        {
            path: '/QrPrint/:id',
            name: 'qrprint',
            component: QrPrint,
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