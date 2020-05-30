<template>
  <div>
    <router-view></router-view>
  </div>
</template>
<script>
</script>
<style>
/*
@import url('https://fonts.googleapis.com/css?family=Chelsea+Market');
*/
@import url("https://fonts.googleapis.com/css?family=Ubuntu:wght@300;400;700");

html,
body {
  /*
  font-family: "Roboto Slab", serif;
  font-family: 'Chelsea Market', cursive;
  */
  font-family: 'Ubuntu', sans-serif;
  background: #d8ebb5;
  background: #faf5e4;
  background: #263f44;
  background: #f0ab00;
  background: #e5e5e7;
  background: #162530;
  background: #2c3e50;
  /*#015668; */
  margin: 0;
  padding: 0;
  background-size: contain;
}
#app {
  /*
  font-family: "Roboto Slab", serif;
  font-family: 'Chelsea Market', cursive;
  */
  font-family: 'Ubuntu', sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: left;
  color: #000;
}
button,
input,
select,
textarea {
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


