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
//Documento: Modulo para gestionar los depositos por solicitud
import BoucherOfRequest from './views/document/BoucherOfRequest'
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
        {
            path: '/',
            name: 'home',
            component: Home,
            children: [
                { path: '', name: 'informationelection', component: InformationElection },
                { path: '/responseinformation/:id_election/:id', name: 'responseinformation', component: ResponseInformation },
                { path: '/responsedatatablets/:id', name: 'responsedatatablets', component: responseDataTablets },
            ],
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
                { path: '/boucherofrequest/:id', name: 'boucherofrequest', component: BoucherOfRequest },

                { path: '/nuevaConvocatoria', name: 'nuevaConvocatoria', component: NuevaConvocatoria },
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