<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>bienvenido</span>
        <el-button style="float: right; padding: 3px 0" type="text"
          >ayuda</el-button
        >
      </div>
      <h1>bienvenido/a</h1>
      <h5>esta pagina a sido intencionalmente puesta en blanco</h5>
      <el-button type="primary" @click="reporte">Reporte</el-button>
      <!-- 
      <example msg="Welcome to Your Vue.js App" />

      <el-button type="text" @click="openModalPerson">Personas</el-button>
      <persona :centerDialogVisible="isVisible" @update-visible="update"></persona>
      <el-button type="text" @click="openModalValued">Valorado</el-button>
      <valorado :centerDialogVisible.sync="isValued"></valorado>
      -->
    </el-card>
  </div>
</template>

<script>
import persona from "./components/Personed";
import example from "./components/example.vue";
import valorado from "./components/Valued";

export default {
  name: "Bienvenido",
  components: {
    persona,
    example,
    valorado,
  },
  data() {
    return {
      isValued: false,
      isVisible: false,
      messages: {},
      data: {},
    };
  },
  mounted() {},
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    openModalPerson() {
      this.isVisible = true;
    },
    openModalValued() {
      this.isValued = true;
    },
    update(isVisible) {
      this.isVisible = isVisible;
    },
    reporte() {
      /*
      axios({
          url: "/api/reportSelectedFixedAssets2/",
          params: {
          },
          method: "GET",
          responseType: "pdf",
        }).then((response) => {
          let blob = new Blob([response.data], {
            type: "application/pdf",
          });
          let link = document.createElement("a");
          link.href = window.URL.createObjectURL(blob);
          let url = window.URL.createObjectURL(blob);
          window.open(url);
        });*/
      axios
        .get("/api/reportSelectedFixedAssets2/", {
          responseType: "arraybuffer",
        })
        .then((response) => {
          let blob = new Blob([response.data], { type: "application/pdf" });
          let link = document.createElement("a");
          //link.href = window.URL.createObjectURL(blob);
          //link.download = "test.pdf";
          //link.click();
          let url = window.URL.createObjectURL(blob);
          window.open(url);
        });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
