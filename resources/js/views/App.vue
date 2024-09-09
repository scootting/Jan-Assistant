<template>
  <div>
    <!--
    <router-view :key="$route.fullPath"></router-view>
    <router-view :key="$route.path"></router-view>
    -->
    <router-view></router-view>
  </div>
</template>
<script>
</script>
<style>
/*
@import url('https://fonts.googleapis.com/css?family=Chelsea+Market');
@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400&display=swap');
*/
@import url('https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap');

/*
@import url("https://fonts.googleapis.com/css?family=Ubuntu+Condensed&display=swap");
@import url("https://fonts.googleapis.com/css?family=Ubuntu:wght@300;400;700");
*/
html,
body {
  /*
  font-family: "Roboto Slab", serif;
  font-family: 'Chelsea Market', cursive;
  font-family: "Ubuntu+Condensed", sans-serif;
  font-family: 'Oswald', sans-serif;
  */
  font-family: 'Source Sans 3', sans-serif;
  background: #d7d9ce;
  margin: 0;
  padding: 0;
  background-size: contain;
}
#app {
  /*
  font-family: "Ubuntu+Condensed", sans-serif;  
  */
  font-family: 'Source Sans 3', sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: left;
  color: #040404;
  text-transform: uppercase;
}
button,
input,
.el-dropdown-menu,
select,
textarea {
  font-family: inherit;
  font-size: inherit;
  line-height: inherit;
  color: inherit;
  text-transform: uppercase;
}
</style>


