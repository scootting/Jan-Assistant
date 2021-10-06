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
      <div>
        <form>
          <el-row>
            <el-col>
              <el-select v-model="value" clearable placeholder="elegir tramite">
                <el-option
                  v-for="item in options"
                  :key="item.cod_con"
                  :label="item.glosa"
                  :value="item.cod_con"
                >
                </el-option>
              </el-select>

              <el-button type="primary" plain>Seleccionar</el-button>
            </el-col>
            <br /><br /><br />
            <el-col>
              <el-table :data="tableData" style="width: 100%">
                <el-table-column
                  fixed
                  prop="tag_doc"
                  label="Codigo doc"
                  width="300"
                >
                  <template slot-scope="scope">
                    <div slot="reference" class="name-wrapper">
                      <el-tag size="medium">{{ scope.row.tag_doc }}</el-tag>
                    </div>
                  </template>
                </el-table-column>
                <el-table-column
                  prop="tip_doc"
                  label="Nombre de documento"
                  width="450"
                >
                </el-table-column>
                <el-table-column prop="zip" label="estado" width="120">
                </el-table-column>
                <el-table-column fixed="right" label="Operaciones" width="250">
                  <template slot-scope="scope">
                    <el-button @click="handleClick" type="text" size="small"
                      >Imprimir</el-button
                    >
                    <el-button
                      @click="detalle(scope.$index, scope.row)"
                      type="text"
                      size="small"
                      >seguimiento</el-button
                    >
                  </template>
                </el-table-column>
              </el-table>
            </el-col>
          </el-row>
        </form>
      </div>
      <el-dialog
        title="Tips"
        :visible.sync="dialogVisible"
        width="50%"
        :before-close="handleClose"
      >
        <span>Detalle de seguimiento de documentacion</span>
        <el-table :data="detail" style="width: 100%">
          <el-table-column prop="des_tra" label="observacion" width="250">
          </el-table-column>
          <el-table-column prop="est_doc" label="estado"> </el-table-column>
        </el-table>
        <span slot="footer" class="dialog-footer">
          <el-button @click="dialogVisible = false">Cancel</el-button>
          <el-button type="primary" @click="dialogVisible = false"
            >Confirm</el-button
          >
        </span>
      </el-dialog>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "Borrador",
  data() {
    return {
      dialogVisible: false,
      ci: this.$store.state.user.nodip,
      ci1: "6600648",
      options: [],
      value: "",
      tableData: [],
      detail:[],
    };
  },
  mounted() {
    console.log(
      "mensaje para ver si se ejecuta la recuperacion de las convocatorias "
    );
    this.getOpcionesConvocatoria();

    this.getConvocatoriasSeleccionadas();
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    handleClose(done) {
      this.$confirm("Are you sure to close this dialog?")
        .then((_) => {
          done();
        })
        .catch((_) => {});
    },
    handleClick() {
      console.log("click");
    },
    detalle(index,row) {
      this.dialogVisible = true;
      axios
        .get("/api/detalleDoc", {
          params: { tag: row.tag_doc },
        })
        .then((data) => {
          this.detail = data.data;
          console.log(data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getOpcionesConvocatoria() {
      axios
        .get("/api/convocatoria/")
        .then((data) => {
          this.options = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getConvocatoriasSeleccionadas() {
      console.log("esto es una prueba", this.ci);
      axios
        .get("/api/tipoDoc", {
          params: { ci: this.ci },
        })
        .then((data) => {
          this.tableData = data.data;
          console.log(data);
        })
        .catch((err) => {
          console.log(err);
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
