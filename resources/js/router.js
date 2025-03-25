import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

// Pages
import NotFound from './views/NotFound'
import Success from './views/Success'
import Login from './views/Login'
import Register from './views/Register'
import Logout from './views/Logout'
import Dashboard from './views/Dashboard'

import Information from './views/clients/Information'
import Password from './views/clients/Password'



//certificado de diplomados
//import AddGraduateCertificate from './views/document/AddGraduateCertificate'

import Welcome from './views/Welcome'
import Home from './views/Home'
import Layout from './views/Layout'
import FinancialStatements from './views/application/FinancialStatements'

//Tesoro: Modulo para seleccionar los valores en linea
import SaleStudents from './views/treasure/SaleStudents'
import CourseStudents from './views/treasure/CourseStudents'
//Documento: Modulo para imprimir los comprobantes de pago por solicitud
import SaleValuesDetails from './views/treasure/SaleValuesDetails'
import InformationSaleDetails from './views/treasure/InformationSaleDetails'
//Documento: Modulo para gestionar las solicitudes
import Requests from './views/document/Requests'
//Documento: Modulo para gestionar las solicitudes
import Courses from './views/document/Courses'

//Documento: Modulo para crear una nueva solicitud 
//  |--------------------------------------------------------------------------
//  | Rutas API para el Sistema de Memoriales Universitarios
//  |--------------------------------------------------------------------------    
//  * M1. Añade una nueva solicitud para la elaboracion de memorial universitario              
import AddRequestMemorial from './views/document/AddRequestMemorial'
//  * M2. Lista las solicitudes de elaboracion de memorial universitario              
import RequestMemorial from './views/document/RequestMemorial'

//  |--------------------------------------------------------------------------
//  | Rutas API para el Sistema de Solvencias Universitarias
//  |--------------------------------------------------------------------------    
//  * S1. Añade una nueva solicitud para la elaboracion de una solvencia universitaria              
import AddRequestSolvencies from './views/document/AddRequestSolvencies'
//  * S2. Lista las solicitudes de la solvencia universitaria              
import RequestSolvencies from './views/document/RequestSolvencies'
//  * S2. Editar las solicitudes de la solvencia universitaria              
import EditRequestSolvencies from './views/document/EditRequestSolvencies'

//  |--------------------------------------------------------------------------
//  | Rutas API para el Sistema de Elecciones
//  |--------------------------------------------------------------------------       
import InformationElection from './views/election/Information.vue'
import ResponseInformation from './views/election/ResponseInformation.vue'
import responseDataTablets from './views/election/responseDataTablets.vue'



// Routes
const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'is-active',
    routes: [
        /*
        {
            path: '/',
            name: 'home',
            component: Home,
            children: [
                { path: '', name: 'layout', component: Layout },
                { path: '/transaction/:id', name: 'informationsaledetails', component: InformationSaleDetails },

                /*
                { path: '', name: 'informationelection', component: InformationElection },
                { path: '/responseinformation/:id_election/:id', name: 'responseinformation', component: ResponseInformation },
                { path: '/responsedatatablets/:id', name: 'responsedatatablets', component: responseDataTablets },
            ],
        },
        */

        {
            path: '/',
            name: 'home',
            component: Home,
            children: [
                { path: '', name: 'layout', component: Layout },
                { path: '/financial', name: 'financialstatements', component: FinancialStatements },
            ],
        },
        {
            path: '/login', //path: '/login',
            name: 'login',
            component: Login,
        },
        {
            path: '/transaction/:id', //path: '/login',
            name: 'informationsaledetails',
            component: InformationSaleDetails,
        },
        {
            path: '/assignment/:id', //path: '/login',
            name: 'assignmentdetails',
            component: AssignmentDetails,
        },
        {
            path: '/register', //path: '/login',
            name: 'register',
            component: Register,
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

                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Sistema de venta de valores en linea
                //  |--------------------------------------------------------------------------    
                { path: '/requests', name: 'requests', component: Requests },
                { path: '/courses', name: 'courses', component: Courses },
                { path: '/salestudents', name: 'salestudents', component: SaleStudents },
                { path: '/coursestudents', name: 'coursestudents', component: CourseStudents },
                { path: '/salevaluesdetails/:id', name: 'salevaluesdetails', component: SaleValuesDetails },
                //{ path: '/boucherofrequest/:id', name: 'boucherofrequest', component: BoucherOfRequest },

                //{ path: '/nuevaConvocatoria', name: 'nuevaConvocatoria', component: NuevaConvocatoria },
                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Sistema de Memoriales Universitarios
                //  |--------------------------------------------------------------------------    
                //  * M1. Añade una nueva solicitud para la elaboracion de memorial universitario              
                { path: '/AddRequestMemorial', name: 'addrequestmemorial', component: AddRequestMemorial },
                //  * M2. Lista las solicitudes de elaboracion de memorial universitario              
                { path: '/RequestMemorial', name: 'requestmemorial', component: RequestMemorial },
                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Sistema de Solvencias Universitarias
                //  |--------------------------------------------------------------------------    
                //  * S1. Añade una nueva solicitud para la elaboracion de una solvencia universitaria              
                { path: '/AddRequestSolvencies', name: 'addrequestsolvencies', component: AddRequestSolvencies },
                //  * S2. Lista las solicitudes de la solvencia universitaria              
                { path: '/RequestSolvencies', name: 'requestsolvencies', component: RequestSolvencies },
                //  * S2. Lista las solicitudes de la solvencia universitaria              
                { path: '/RequestSolvencies/:id', name: 'editrequestsolvencies', component: EditRequestSolvencies },
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
            path: '/Success',
            name: 'success',
            component: Success,
        },
        {
            path: '*',
            redirect: '/404',
        },
    ],
});

export default router