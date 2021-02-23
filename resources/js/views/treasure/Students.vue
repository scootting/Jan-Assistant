<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>dia de venta: {{ day }}</span>
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
            @click="initGetDataOfStudent"
          ></el-button>
        </el-input>
      </div>
      <br />
      <el-tag type="success">{{ texto }}</el-tag>
      <el-row :gutter="20">
        <el-col :span="11"
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
        <el-col :span="13"
          ><div class="grid-content bg-purple">
            <el-table
              :data="valuesPostulations"
              border
              show-summary
              style="width: 100%"
              size="small"
            >
              <el-table-column prop="cod_val" label="cod." width="65"> </el-table-column>
              <el-table-column prop="des_val" label="descripcion" width="450">
              </el-table-column>
              <el-table-column prop="pre_uni_val" sortable label="Precio" align="right">
              </el-table-column>
            </el-table></div
        ></el-col>
      </el-row>
      <el-button type="primary" size="small" @click="saveTransaction()"
        >guardar</el-button
      >
      <el-button type="primary" size="small" @click="printTransactions()"
        >imprimir</el-button
      >
      <el-button size="small" @click="resetTransaction()">cancel</el-button>
      <el-row> </el-row>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "",
  data() {
    return {
      writtenTextParameter: "",
      user: this.$store.state.user,
      day: "",
      saleOfDay: [],
      valuesPostulations: [],
      postulations: {
        nro_dip: "",
        paterno: "",
        materno: "",
        nombres: "",
        modalidad: "",
        id_modalidad: "",
      },
      texto: "",
    };
  },
  mounted() {
    let app = this;
    app.day = app.$route.params.id;
    axios
      .post("/api/getSaleOfDayById", {
        id: app.day,
        user: app.user.usuario,
        year: app.user.gestion,
      })
      .then(function (response) {
        app.saleOfDay = response.data[0];
        if (app.saleOfDay.estado == "V") alert("El dia ya esta verificado");
      })
      .catch(function (response) {
        alert("no se puede crear el registro de los valores del estudiante");
      });
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    resetTransaction() {
      alert("se esta reseteando todo");
    },
    saveTransaction() {
      var app = this;
      var newDayTransactions = app.saleOfDay;
      var newPostulations = app.postulations;
      var newValuesPostulations = app.valuesPostulations;
      axios
        .post("/api/valuesforStudent", {
          dayTransactions: newDayTransactions,
          postulations: newPostulations,
          valuesPostulations: newValuesPostulations,
          marker: "registrar",
        })
        .then(function (response) {
          alert("se ha creado el registro de los valores del estudiante");
        })
        .catch(function (response) {
          console.log(response);
          alert("no se puede crear el registro de los valores del estudiante");
        });
    },
    printTransactions() {
      axios({
        url: "/api/reports/lionel", //+ this.oficina.cod_soa,
        method: "GET",
        responseType: "blob",
      }).then((response) => {
        console.log(response.data);
        console.log("1");
        let blob = new Blob([response.data], {
          type: "application/pdf",
        });
        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        console.log(blob);
        let url = window.URL.createObjectURL(blob);
        window.open(url);
      });
    },
    initGetDataOfStudent() {
      let app = this;
      alert(app.user.gestion);
      axios
        .post("/api/getDataOfStudentById", {
          id: app.writtenTextParameter,
          year: app.user.gestion,
        })
        .then((response) => {
          app.postulations = response.data[0];
          app.texto = JSON.stringify(app.postulations);
          /*de acuerdo a la postulacion se debe imprimir los valores*/
          axios
            .post("/api/valuesprocedure", {
              id: app.postulations.id_modalidad,
              year: app.user.gestion,
            })
            .then((response) => {
              app.valuesPostulations = response.data;
              app.texto = JSON.stringify(app.postulations);
              /*de acuerdo a la postulacion se debe imprimir los valores*/
            })
            .catch((error) => {
              this.error = error.response.data;
              this.$notify.error({
                title: "GRAN ERROR",
                message: this.error.message,
              });
            });
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
        callback: (action) => {},
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
