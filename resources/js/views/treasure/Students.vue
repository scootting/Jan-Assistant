<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>venta de valores para estudiantes nuevos</span>
        <el-button
          style="float: right; padding: 3px 0"
          type="text"
          @click="test"
          >ayuda</el-button
        >
      </div>
      <div style="margin-top: 15px">
        <el-input
          placeholder="INSERTE EL NUMERO DE CARNET DEL INTERESADO"
          v-model="writtenTextParameter"
          class="input-with-select"
        >
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="initSearchNewStudent"
          ></el-button>
        </el-input>
      </div>
      <br />
    </el-card>
  </div>
</template>

<script>
export default {
  name: "VentaEstudiantesNuevos",
  data() {
    return {
      writtenTextParameter: "",
      year: '',
      messages: {},
      students: [],
    };
  },
  mounted() {
      this.year = '2019';
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    initSearchNewStudent() {
      let app = this;
      axios
        .post("/api/newstudent", {
          id: app.writtenTextParameter,
          year: app.year,
        })
        .then((response) => {
          app.students = response.data.data;
        })
        .catch((error) => {
          this.error = error;
          this.$notify.error({
            title: "Error",
            message: this.error.message,
          });
        });

      this.$alert(this.writtenTextParameter, "mensaje del alumno", {
        confirmButtonText: "OK",
        callback: (action) => {
          /*
          this.$message({
            type: "info",
            message: `action: ${action}`,
          });*/
        },
      });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
