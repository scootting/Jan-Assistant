import Vue from "vue";
import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";
import axios from "axios";
Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        token: localStorage.getItem("access_token") || null,
        user: {} || null,
    },
    plugins: [createPersistedState()],
    getters: {
        loggedIn(state) {
            return state.token !== null;
        }
    },
    mutations: {
        retrieveToken(state, { user, token }) { 
            state.token = token;
            //console.log(state.token + " token " + token);
            //state.user = JSON.stringify(user);
            state.user = user[0]; 
            //console.log(JSON.stringify(state.user) + " -- " + user.id);
        },
        destroyToken(state) {
            state.token = null;
            state.user = {};
        },
        updateUser(state, user){
            state.user = user;
        }
    },
    actions: {
        retrieveToken({ commit }, credentials) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/api/login", {
                        username: credentials.username,
                        password: credentials.password
                    })
                    .then(response => {
                        const token = response.data.access_token;
                        const user = response.data.user;
                        localStorage.setItem("access_token", token);
                        //localStorage.setItem("user", user);
                        axios.defaults.headers.common["Authorization"] =
                            "Bearer " + token;

                        //console.log("Cabeceras: ", axios.defaults.headers);
                        console.log("antes de: " + user);
                        commit("retrieveToken", { user, token });
                        //context.commit("retrieveToken", token, user);
                        console.log("despues de: " + user);
                        resolve(response);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        destroyToken(context) {
            if (context.getters.loggedIn) {
                return new Promise((resolve, reject) => {
                    axios
                        .post("/api/logout", "", {
                            headers: {
                                Authorization: "Bearer " + context.state.token
                            }
                        })
                        .then(response => {
                            localStorage.removeItem("access_token");
                            context.commit("destroyToken");

                            resolve(response);
                        })
                        .catch(error => {
                            localStorage.removeItem("access_token");
                            context.commit("destroyToken");

                            reject(error);
                        });
                });
            }
        }
    },
});

export default store;
