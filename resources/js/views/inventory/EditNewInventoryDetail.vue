<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Editar inventario de : {{ EditInvent.unidad }}</span>
      </div>
      <br />
      <br />
      <el-form label-width="160px" :inline="true" size="normal"> </el-form>
      <div class="grid-content bg-purple">
        <el-row :gutter="20">
          <el-col :span="24" :offset="0">
            <el-form label-width="180px" :inline="true" size="small">
              <el-form-item label="Oficina:">
            <el-input v-model="EditInvent.unidad" style="width: 200px" disabled></el-input> 
          </el-form-item> 
          
          <el-form-item label="Sub Oficinas:">
            <el-tag
              v-for="sub_oficina in EditInvent.subUnidades"
              :key="sub_oficina.id"
              type="default"
              size="normal"
              effect="dark"
              >{{ sub_oficina.descripcion }}</el-tag
            >
          </el-form-item>
          <el-form-item label="Cargos:">
            <el-tag
              v-for="cargos in EditInvent.cargos"
              :key="cargos.id"
              type="default"
              size="normal"
              effect="dark"
              >{{ cargos.descripcion }}</el-tag
            >
          </el-form-item>
          <el-form-item label="Encargados:">
            <el-tag
              v-for="encargado in EditInvent.encargados"
              :key="encargado.nro_dip"
              type="default"
              size="normal"
              effect="dark"
              >{{ formatResponsable(encargado).responsable }}</el-tag
            >
          </el-form-item>
            </el-form>
          </el-col>
        </el-row>
      </div>
      <div>
        <el-divider content-position="left">Ingresar datos</el-divider>
        <el-form label-width="180px" :inline="false" size="small">
          <el-form-item label="Fecha de creación">
            <el-date-picker
              v-model="EditInvent.date"
              type="datetime"
              placeholder="Selecionar Fecha"
            >
            </el-date-picker>
          </el-form-item>
          
          <el-form-item size="small" label="Encargados de Inventario">
            <el-select
              class="enc-select"
              v-model="EditInvent.encargados"
              multiple
              placeholder="Seleccione encargados a realizar inventario"
              maxlength="30"
            >
              <el-option
                v-for="(item, index) in encargados"
                :key="index"
                :label="item.nro_dip + '-' +item.paterno + ' ' + item.nombres"
                :value="item.nro_dip"
              >
              </el-option>
            </el-select>
            <el-button
              type="primary"
              size="mini"
              @click="showDialogEncargado = true"
              >Buscar</el-button
            >
          </el-form-item>
          <el-form-item size="small">
              <el-button type="primary" size="mini" @click="saveInventory">Guardar información</el-button>
              <el-button type="danger" size="mini" @click="returnPage">Cancelar</el-button>
          </el-form-item>
          
        </el-form>

      </div>
    </el-card>
        <el-dialog
      title="Buscar Encargado"
      :visible.sync="showDialogEncargado"
      width="30%"
      @close="showDialogEncargado = false"
    >
      <el-select
        v-model="selectEncargado"
        placeholder="Busque un carnet"
        :loading="searchEncargadoLoading"
        clearable
        filterable
        remote
        :remote-method="getEncargados"
      >
        <el-option
          v-for="item in searchEncargados"
          :key="item.nro_dip"
          :label="item.paterno + ' ' + item.nombres"
          :value="item.nro_dip"
        >
        </el-option>
      </el-select>
      <span slot="footer">
        <el-button @click="onCancelDialog">Cancel</el-button>
        <el-button type="primary" @click="onConfirmDialog">CONFIRMAR</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: "EditNewInventoryDetail",
  data() {
    return {
      loading: false,
      user: this.$store.state.user,
      gestion: this.$store.state.user.gestion,
      doc_inv:null,
      //encargados = los que haran el inventario ,  responsables = a quienes se asigna los activos
      EditInvent: {
        date: "",
        no_doc: "",
        no_doc: "",
        res_enc: [],
        car_cod: [],
        ofc_cod:"",
        sub_ofc_cod:[],
        ci_res: [],
        car_cod_resp: [],
      },
      unidades: [],
      subUnidades: [],
      cargos: [],
      responsables: [],
      encargados: [],
      searchEncargados: [],
      selectEncargado: null,
      searchEncargadoLoading: false,
      subUnidadesLoading: false,
      unidadLoading: false,
      cargosLoading: false,
      responsablesLoading: false,
      showDialogEncargado: false,
    };
  },

created(){
    this.id = this.$route.params.id;
    axios
      .get("/api/inventory2/edit/" + this.id)
      .then((response) => {
        this.editForm = response.data;
        this.getSubUnidades(this.editForm.ofc_cod,false);
        this.remoteMethod(this.editForm.ofc_cod);
        this.editForm.res_enc.forEach(nd => {
          	this.getEncargados(nd,(list)=>{
              	list.forEach(en=>{
                    if(en.nro_dip===nd.trim())
                      this.encargados.push(en);
                });
            });
        });
      })
      .catch((error) => {
        this.error = error;
        this.$notify.error({
          title: "Error",
          message: this.error.message,
        });
      });
},
  methods: {
    getEncargados(nro_dip) {
      this.searchEncargadoLoading = true;
      axios
        .get("/api/inventory2/encargados", {
          params: { nro_dip: nro_dip },
        })
        .then((data) => {
          this.searchEncargadoLoading = false;
          this.searchEncargados = Object.values(data.data.data);
          console.log(data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getDocInventory() {
      axios
        .get("/api/inventory2/doc_inv/" + this.doc_inv_no_cod)
        .then((data) => {
          this.doc_inv = data.data;
          this.getActivesSearch();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    formatResponsable(responsable) {
      return {
        cargo: responsable.descripcion,
        responsable:
          responsable.paterno.trim() +
          " " +
          responsable.materno.trim() +
          " " +
          responsable.nombres.trim(),
        nro_dip: responsable.nro_dip,
      };
    },
    saveInventory() {
      axios
        .post("/api/inventory2/save", this.EditInvent)
        .then((data) => {
          this.$message({
            message: "Cambiado exitosamente",
            type: "success",
            duration: 5000,
            showClose: true,
          });
          this.No_Doc = data.data.no_doc;
          this.guardado = true;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onCancelDialog() {
      this.selectEncargado = null;
      this.showDialogEncargado = false;
    },
    onConfirmDialog() {
      if (!this.selectEncargado) {
        this.$message({
          message: "NO selecciono ningun encargado",
          type: "warning",
          showClose: true,
          duaration: 5000,
        });
        return;
      }
      let addEncargado = this.searchEncargados.filter((e) => {
        return e.nro_dip === this.selectEncargado;
      })[0];
      this.EditInvent.encargados.push(addEncargado.nro_dip);
      this.encargados.push(addEncargado);
      this.selectEncargado = null;
      this.showDialogEncargado = false;
    },
    returnPage() {
      this.$notify.info({
        title: "Edición cancelada",
        message: "No se edito inventario",
        duration: 0,
      });
      this.$router.push({
        name: "inventory2",
      });
    },
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