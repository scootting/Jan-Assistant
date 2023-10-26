<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>bienvenido</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <h5>esta pagina a sido intencionalmente puesta en blanco</h5>
      <el-button type="primary" @click="test">Curl</el-button>
    </el-card>
  </div>
</template>

<script>
import example from "./components/example.vue";

export default {
  name: "Bienvenido",
  data() {
    return {
      data:[],
      dataResponse:{},
    };
  },
  mounted() { },
  methods: {
    async test() {
      var app = this;
      try {
        let response = await axios.post("/api/storeOnlineSalesRequest", {
        });
        app.loading = false;
        app.data = response.data;
      } catch (error) {
        console.log(error);
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
