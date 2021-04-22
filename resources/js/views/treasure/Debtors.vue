<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>identificador del dia {{ day }}</span>
        <el-button
          style="float: right; padding: 3px 0"
          type="text"
          @click="test"
          >ayuda</el-button
        >
      </div>
      <br />
      <!--
        <el-tag type="success">{{ texto }}</el-tag>        
      -->
      <el-row :gutter="20">
        <el-col :span="24"
          ><div class="grid-content bg-purple">
            <el-form
              ref="form"
              :model="this.debtor"
              label-width="200px"
              size="mini"
            >
              <el-form-item label="codigo del valor">
                <el-col :span="8">
                  <el-input
                    v-model="debtor.cod_val"
                    ref="cod_val"
                    @keyup.enter.native="initSearchValue"
                  ></el-input>
                </el-col>
                <el-col :span="16">
                  <el-input v-model="debtor.des_val" disabled></el-input>
                </el-col>
              </el-form-item>
              <el-form-item label="carnet de identidad">
                <el-col :span="8">
                  <el-input
                    v-model="debtor.nro_dip"
                    ref="nro_dip"
                    @keyup.enter.native="initSearchPerson"
                  ></el-input>
                </el-col>
                <el-col :span="16">
                  <el-input v-model="debtor.des_per" disabled></el-input>
                </el-col>
              </el-form-item>
              <el-form-item label="numero de cuenta">
                <el-col :span="8">
                  <el-input v-model="debtor.nro_cta" ref="nro_cta"></el-input>
                </el-col>
                <el-col :span="16">
                  <el-input v-model="debtor.des_cta" disabled></el-input>
                </el-col>
              </el-form-item>
              <el-form-item label="numero de deposito">
                <el-col :span="24">
                  <el-input v-model="debtor.nro_dep" ref="nro_dep"></el-input>
                </el-col>
              </el-form-item>
              <el-form-item label="importe">
                <el-col :span="24">
                  <el-input v-model="debtor.importe" ref="importe"></el-input>
                </el-col>
              </el-form-item>
            </el-form></div
        ></el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="24">
          <el-button type="success" size="small" @click="appendDebtor()"
            >agregar</el-button
          >
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="24"
          ><div class="grid-content bg-purple">
            <el-table
              :data="dataDebtors"
              border
              show-summary
              style="width: 100%"
              size="small"
            >
              <el-table-column prop="cod_val" label="cod." width="65">
              </el-table-column>
              <el-table-column prop="des_val" label="descripcion" width="550">
              </el-table-column>
              <el-table-column
                prop="pre_uni_val"
                sortable
                label="Precio"
                align="right"
              >
              </el-table-column>
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
      user: this.$store.state.user,
      day: "",
      debtor: {
        cod_val: "",
        des_val: "",
        nro_dip: "",
        des_per: "",
        nro_cta: "",
        des_cta: "",
        nro_dep: "",
        importe: 0,
      },
      texto: "",
      dataDebtors: [],
      selectedValue: [],
    };
  },
  mounted() {
    let app = this;
    app.day = app.$route.params.id;
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
      //this.$refs.nocuenta.focus();
      //this.$nextTick(() => this.$refs.nocuenta.focus())
    },
    initSearchValue() {
      let app = this;
      axios
        .post("/api/getValueById", {
          id: app.debtor.cod_val,
          year: app.user.gestion,
        })
        .then(function (response) {
          app.selectedValue = response.data[0];
          console.log(response);
          app.debtor.des_val = app.selectedValue.des_val;
          app.$refs.nro_dip.focus();
          //this.$nextTick(() => this.$refs.nro_dip.focus());
        })
        .catch(function (response) {
          alert(
            "el valor universitario con el identificador no corresponde a uno valido"
          );
        });
    },
    initSearchPerson() {
      let app = this;
      let id = this.debtor.nro_dip;
      axios
        .get("/api/person/" + id)
        .then(function (response) {
          console.log(response.data);
          app.selectedPerson = response.data[0];
          app.debtor.des_per =
            app.selectedPerson.paterno +" "+
            app.selectedPerson.materno +","+
            app.selectedPerson.nombres;
          app.$nextTick(() => app.$refs.nro_cta.focus());
        })
        .catch(function () {
          alert("No se puede hallar el registro de la persona indicada");
        });
    },

    appendDebtor() {
      var app = this;
    },
    saveTransaction() {
      var app = this;
    },
    printTransactions() {},
    resetTransaction() {},
  },
};
</script>

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
