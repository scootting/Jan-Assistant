/******************** Repository js ****************/
import axios from "axios";
import router from "./router";

const baseDomain = "http://apiendpoint.com/api/";
const baseURL = `${baseDomain}/api`;

const api = axios.create({
    baseURL, // headers: {
    //  'Authorization': 'Bearer ' + localStorage.getItem('api_token')
    // },
    validateStatus: function(status) {
        if (status == 401) {
            router.push("/login");
        } else {
            return status;
        }
    }
});
api.interceptors.request.use(
    function(config) {
        const token = localStorage.getItem("token");

        if (token == null) {
            console.log("Token Is empty");
            console.log("Redirecting to Login");
            router.push({ name: "login" });
        }

        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    function(response) {
        return response;
        console.log(response);
    },
    function(error) {
        console.log(error);

        return error;
    }
);
// Add a response interceptor
api.interceptors.response.use(
    function(response) {
        // Do something with response data
        return response;
    },
    function(error) {
        
        // Do something with response error
        console.log("Error Found");

        return Promise.reject(error);
    }
);

//este es lo que vale para el response
axios.interceptors.response.use(undefined, function (err) {
    return new Promise(function (resolve, reject) {
      if (err.status === 401 && err.config && !err.config.__isRetryRequest) {
      // if you ever get an unauthorized, logout the user
        this.$store.dispatch(AUTH_LOGOUT)
      // you can also redirect to /login if needed !
      }
      throw err;
    });
  });



  instance.interceptors.response.use(function (response) {
    const newtoken = get(response, 'headers.authorization')
    if (newtoken) store.dispatch('setToken', newtoken)
    console.log(response.data)
    return response
  }, function (error) {
    return Promise.reject(error)
  })

  return instance


export default api;