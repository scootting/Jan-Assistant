<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>informacion personal</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
      </div>
      <el-row :gutter="20">
        <p>
          <el-alert
            title="El cambio de la direccion, telefono, correo implica que sus nuevas solicitudes tendrán esta nueva informacion."
            type="success">
          </el-alert>
        </p>
        <el-col :span="12">
          <div class="grid-content bg-purple">
            <p>valores ofertados</p>
            <el-table v-loading="loading" :data="offered" style="width: 100%">
              <el-table-column prop="des_val" label="descripcion" width="350"></el-table-column>
              <el-table-column prop="pre_uni" label="precio" width="100" align="right"></el-table-column>
              <el-table-column align="right" width="100">
                <template slot-scope="scope">
                  <el-button @click="initAddValues(scope.$index, scope.row)" type="primary" size="mini" plain>Agregar
                  </el-button>
                </template>
              </el-table-column>
            </el-table>
          </div>
        </el-col>
        <el-col :span="12">
          <div class="grid-content bg-purple">
            <p>valores adquiridos</p>
          </div>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "",
  data() {
    return {
      client: this.$store.state.user,
      loading: true,
      offered: [],
      acquired: [],

    };
  },
  mounted() {
    this.getValuesOffered();
  },
  methods: {
    test(){
      alert("llamar al 74246032 para asistencia tecnica");
    },
    async getValuesOffered() {
      var app = this;
      try {
        let response = await axios.post("/api/getValuesOffered/", {
          year: app.client.gestion,
        });
        app.loading = false;
        app.offered = response.data;
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },
    initAddValues(index, row) {
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
