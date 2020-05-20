<template>
  <div>
    <router-view></router-view>
  </div>
</template>
<script>
</script>
<style>

@import url('https://fonts.googleapis.com/css?family=Roboto+Condensed');
html, body {
  font-family: 'Roboto Condensed', sans-serif;
  /*background: #102a43;*/
  background: #d8ebb5;
  background: #faf5e4;
  background: #263f44;
  background: #F0AB00;
  background: #E5E5E7;
  background: #162530;
  background: #2c3e50;
  /*#015668; */
  margin: 0;
  padding: 0;
  background-size: contain;  
}
#app {
  font-family: 'Roboto Condensed', sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: left;
  color: #000;

}  
button, input, select, textarea
{
  font-family: inherit;
  font-size: inherit;
  line-height: inherit;
  color: inherit;
}

/*
axios.interceptors.response.use(function (response) {
  return response;
}, function (error) {

  const originalRequest = error.config;

  if (error.response.status === 401 && !originalRequest._retry) {

    originalRequest._retry = true;

    const refreshToken = window.localStorage.getItem('refreshToken');
    return axios.post('http://localhost:8000/auth/refresh', { refreshToken })
      .then(({data}) => {
        window.localStorage.setItem('token', data.token);
        window.localStorage.setItem('refreshToken', data.refreshToken);
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + data.token;
        originalRequest.headers['Authorization'] = 'Bearer ' + data.token;
        return axios(originalRequest);
      });
  }

  return Promise.reject(error);
});
*/ 

</style>


