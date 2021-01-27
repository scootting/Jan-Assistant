<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>venta de valores para estudiantes nuevos</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="test"
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
      <el-tag type="success">{{ texto }}</el-tag>
      <el-row :gutter="20">
        <el-col :span="12"
          ><div class="grid-content bg-purple">
            <el-form
              ref="form"
              :model="this.postulations"
              label-width="200px"
              size="mini"
            >
              <el-form-item label="carnet de identidad">
                <el-input v-model="postulations.nro_dip" disabled></el-input>
              </el-form-item>
              <el-form-item label="apellido paterno">
                <el-input v-model="postulations.paterno" disabled></el-input>
              </el-form-item>
              <el-form-item label="apellido materno">
                <el-input v-model="postulations.materno" disabled></el-input>
              </el-form-item>
              <el-form-item label="nombres">
                <el-input v-model="postulations.nombres" disabled></el-input>
              </el-form-item>
              <el-form-item label="modalidad de ingreso">
                <el-input v-model="postulations.modalidad" disabled></el-input>
              </el-form-item>
            </el-form></div
        ></el-col>
        <el-col :span="12"><div class="grid-content bg-purple"></div></el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "VentaEstudiantesNuevos",
  data() {
    return {
      writtenTextParameter: "",
      year: "",
      messages: {},
      postulations: {
        nro_dip: "",
        paterno: "",
        materno: "",
        nombres: "",
        modalidad: "",
      },
      requisites: {},
      texto: "Loasdasdsadsad",
    };
  },
  mounted() {
    this.year = "2020";
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
          app.postulations = response.data[0];
          app.texto = JSON.stringify(app.postulations);
          /*de acuerdo a la postulacion se debe imprimir los valores*/
        })
        .catch((error) => {
          this.error = error.response.data;
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
<style scoped>
.el-row {
  margin-bottom: 20px;
}
.el-col {
  border-radius: 4px;
}
.bg-purple-dark {
  background: #99a9bf;
}
.bg-purple {
  background: #d3dce6;
}
.bg-purple-light {
  background: #e5e9f2;
}
.grid-content {
  border-radius: 4px;
  padding: 15px;
  min-height: 36px;
}
.row-bg {
  padding: 10px 0;
  background-color: #f9fafc;
}
</style>
