<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>certificado de no tener cuentas pendientes </span>
        <el-button
          style="float: right; padding: 3px 0"
          type="text"
          @click="test"
          >ayuda</el-button
        >
      </div>
      <div style="margin-top: 15px">
        <el-input
          placeholder="INSERTE SU NUMERO DE CARNET DE IDENTIDAD"
          v-model="writtenTextParameter"
          class="input-with-select"
        >
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="initGetDataOfPerson"
          ></el-button>
        </el-input>
      </div>
      <br />
      <!--
        <el-tag type="success">{{ texto }}</el-tag>        
      -->
      <el-row :gutter="20">
        <el-col :span="11"
          ><div class="grid-content bg-purple">
            <p>datos personales</p>
            <el-form
              ref="form"
              :model="this.person"
              label-width="200px"
              size="mini"
            >
              <el-form-item label="carnet de identidad">
                <el-input v-model="person.personal" disabled></el-input>
              </el-form-item>
              <el-form-item label="apellido paterno">
                <el-input v-model="person.paterno" disabled></el-input>
              </el-form-item>
              <el-form-item label="apellido materno">
                <el-input v-model="person.materno" disabled></el-input>
              </el-form-item>
              <el-form-item label="nombres">
                <el-input v-model="person.nombres" disabled></el-input>
              </el-form-item>
            </el-form></div
        ></el-col>
        <el-col :span="13"
          ><div class="grid-content bg-purple">
            <p>convocatorias</p>
            <!---->
            <el-table
              v-loading="loading"
              :data="descriptions"
              style="width: 100%"
              height="250"
              @selection-change="handleSelectionChange"
            >
              <el-table-column type="selection" width="55"> </el-table-column>
              <el-table-column prop="fec_pre" label="fecha" width="120">
                <template slot-scope="scope">
                  <el-tag size="success" type="info">{{
                    scope.row.fec_pre
                  }}</el-tag>
                </template>
              </el-table-column>
              <el-table-column
                prop="glosa"
                label="descripcion"
                width="420"
              ></el-table-column>
            </el-table></div
        ></el-col>
      </el-row>
      <el-button type="success" size="small" @click="saveTransaction()"
        >guardar</el-button
      >
      <el-button type="primary" size="small" @click="printTransactions()"
        >imprimir</el-button
      >
      <el-button size="small" @click="resetTransaction()">nuevo</el-button>
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
      descriptions: [],
      person: {},
      loading: false,
    };
  },
  mounted() {
    let app = this;
    console.log('NDEU')
    let abr = "NDEU";
    axios
      .get("/description/" + abr)
      .then(function (response) {
        console.log(response.data);
        app.descriptions = response.data;
      })
      .catch(function () {
        alert("No se puede hallar el registro de la persona indicada");
      });    /*
*/
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    saveTransaction() {
      /*
      var app = this;
      var newDayTransactions = app.saleOfDay;
      var newPostulations = app.postulations;
      var newValuesPostulations = app.valuesPostulations;
      if (app.activation != 2) {
        alert("no puede realizar esta accion");
        return;
      }
      axios
        .post("/api/storeTransactionsByStudents", {
          dayTransactions: newDayTransactions,
          postulations: newPostulations,
          valuesPostulations: newValuesPostulations,
          marker: "registrar",
        })
        .then(function (response) {
          app.$alert(
            "se ha creado el registro de los valores del estudiante",
            "mensaje del sistema",
            {
              confirmButtonText: "OK",
              callback: (action) => {},
            }
          );
          //alert("se ha creado el registro de los valores del estudiante");
          app.activation = 3;
        })
        .catch(function (response) {
          console.log(response);
          alert("no se puede crear el registro de los valores del estudiante");
        });*/
    },
    initGetDataOfPerson() {
      let app = this;
      let id = app.writtenTextParameter;
      axios
        .get("/person/" + id)
        .then(function (response) {
          console.log(response.data);
          app.person = response.data[0];
        })
        .catch(function () {
          alert("No se puede hallar el registro de la persona indicada");
        });
    },
    initGetDataofDescription() {
      let app = this;
      let abr = "NDEU";
      axios
        .get("/description/" + abr)
        .then(function (response) {
          console.log(response.data);
          app.descriptions = response.data[0];
        })
        .catch(function () {
          alert("No se puede hallar el registro de la persona indicada");
        });
    },
    printTransactions() {
      /*
      var app = this;
      app.ci_per = app.postulations.nro_dip;
      if (this.activation != 3) {
        alert("no puede realizar esta accion");
        return;
      }
      axios({
        url:
          "/api/reports/" +
          app.day +
          "/" +
          app.ci_per +
          "/" +
          app.user.gestion +
          "/" +
          app.user.usuario,
        method: "GET",
        responseType: "blob",
      }).then((response) => {
        let blob = new Blob([response.data], {
          type: "application/pdf",
        });
        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        let url = window.URL.createObjectURL(blob);
        window.open(url);
        this.activation = 4;
      });*/
    },
    resetTransaction() {},
    handleSelectionChange(val) {
      console.log(val);
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
