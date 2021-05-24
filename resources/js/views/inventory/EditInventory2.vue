<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Inventario Editado</span>
        <input
          style="text-align: right; float: right"
          type="text"
          disabled
          v-bind:value="editForm.no_cod"
          placeholder="Nro de documento"
        />
      </div>
      <div class="grid-content bg-purple">
        <el-row>
          <el-form label-width="160px" :inline="true" size="small">
            <el-form-item label="Unidad " size="small">
              <el-select
                v-model="editForm.ofc_cod"
                filterable
                remote
                reserve-keyword
                placeholder="Seleccione una Unidad"
                :remote-method="remoteMethod"
                maxlength="30"
                style="width: 250"
                :loading="unidadLoading" 
                disabled
              >
                <el-option
                  v-for="(item, index) in unidades"
                  :key="index"
                  :label="item.descripcion"
                  :value="item.cod_soa"
                >
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Sub-Unidad" size="small">
              <el-select
                v-model="editForm.sub_ofc_cod"
                filterable
                remote
                multiple
                reserve-keyword
                placeholder="Sub Oficina"
                :remote-method="getSubUnidades"
                maxlength="30"
                style="width: 250"
                disabled
                
              >
                <el-option
                  v-for="(item, index) in subUnidades"
                  :key="index"
                  :label="item.descripcion"
                  :value="item.id"
                >
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Cargo" size="small">
              <el-select
                v-model="editForm.car_cod_resp"
                filterable
                remote
                multiple
                reserve-keyword
                placeholder="Cargos"
                :remote-method="getCargosResp"
                maxlength="30"
                style="width: 250px"
                @change="onChangeCargos"
                :loading="cargosLoading"
                disabled
              >
                <el-option
                  v-for="(item, index) in cargos"
                  :key="index"
                  :label="item.cargo"
                  :value="item.id"
                >
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Responsable" size="small">
              <el-select
                v-model="editForm.ci_res"
                filterable
                remote
                multiple
                reserve-keyword
                placeholder="Responsables"
                :remote-method="getResponsables"
                maxlength="30"
                style="width: 250px"
                :loading="responsablesLoading"
                disabled
              >
                <el-option
                  v-for="(item, index) in responsables"
                  :key="index"
                  :label="item.nombres + item.paterno"
                  :value="item.nro_dip"
                >
                </el-option>
              </el-select>
            </el-form-item> 
          </el-form>
        </el-row>
      </div>
      <div>
         <el-divider content-position="left">Datos editables</el-divider>
             <el-form  label-width="160px" :inline="false" size="small">
               <el-form-item label="fecha de inicio" size="small">
                 <el-date-picker
              v-model="editForm.fec_cre"
              type="datetime"
              placeholder="Selecionar Fecha"
            >
            </el-date-picker>
            </el-form-item>
            <el-form-item size="small" label="Encargados">
              <el-select
                class="enc-select"
                v-model="editForm.res_enc"
                multiple
                placeholder="Seleccione Encargados para Inventario"
                maxlength="30"
              >
                <el-option
                  v-for="(item, index) in encargados"
                  :key="index"
                  :label="item.paterno + ' ' + item.nombres"
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
            <el-form-item label="" size="small">
              <el-row type="flex" justify="end">
                <el-button type="prymary" size="default" @click="saveInventory"
                  >Guardar Cambios</el-button
                >
                <el-button type="default" size="default" @click="initNewInventory"
                  >Atras</el-button
                >
              </el-row>
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
        placeholder="busque un carnet"
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
        <el-button type="primary" @click="onConfirmDialog">Confirmar</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: "Edit_Inventory2",
  data() {
    return {
      id:"",
      editForm: {
        no_doc: "",
        res_enc: [],
        car_cod: [],
        ofc_cod:"",
        sub_ofc_cod:[],
        ci_res: [],
        car_cod_resp: [],
        //estado: [],
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
  created() {
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
    remoteMethod(keyWord) {
      axios
        .get("/api/inventory2/unidad/", {
          params: { keyWord: keyWord.toUpperCase() },
        })
        .then((data) => {
          this.unidades = Object.values(data.data.data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onChangeUnidad(cod_soa) {
      this.getSubUnidades(cod_soa,true);
    },
    onChangeSubUnidades(subUnidades) {
      let cod_soa = this.editForm.ofc_cod;
      this.getCargosResp(cod_soa, subUnidades,true); //subUnidades
      //this.getResponsables(cod_soa, cargos,true);
    },
    onChangeCargos(cargos) {
      let cod_soa = this.editForm.ofc_cod;
      this.getResponsables(cod_soa, cargos,true);
    },
    getSubUnidades(cod_soa,actualizar) {
      this.unidadesLoading = true;
      this.subUnidadesLoading = true;
      axios
        .get("/api/inventory2/sub_unidad", {
          params: { cod_soa: cod_soa },
        })
        .then((data) => {
          this.subUnidadesLoading = false;
          this.unidadesLoading = false;
          this.subUnidades = data.data;
          if(actualizar)
            this.editForm.sub_ofc_cod = this.subUnidades.map((su) => su.id);
          this.getCargosResp(this.editForm.ofc_cod, this.editForm.sub_ofc_cod,actualizar);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getCargosResp(cod_soa, subUnidades, actualizar) {
      this.cargosLoading = true;
      this.subUnidadesLoading = true;
      axios
        .get("/api/inventory2/cargos", {
          params: {
            cod_soa: cod_soa,
            sub_unidades: subUnidades,
          },
        })
        .then((data) => {
          this.cargosLoading = false;
          this.subUnidadesLoading = false;
          this.cargos = data.data;
          if(actualizar)
            this.editForm.car_cod_resp = this.cargos.map((car) => car.id);
          this.getResponsables(this.editForm.ofc_cod, this.editForm.car_cod_resp,actualizar);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getResponsables(cod_soa, cargos,actualizar) {
      this.cargosLoading = true;
      this.responsablesLoading = true;
      axios
        .get("/api/inventory2/responsables", {
          params: {
            cod_soa: cod_soa,
            cargos: cargos,
          },
        })
        .then((data) => {
          this.cargosLoading = false;
          this.responsablesLoading = false;
          this.responsables = data.data;
          this.responsables = this.responsables.map(r=>{
            r.nro_dip = r.nro_dip.trim();
            return r;
          })
          if(actualizar)
            this.editForm.ci_res = this.responsables.map(
              (resp) => resp.nro_dip
            );
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getEncargados(nro_dip,cb = false) {
      this.searchEncargadoLoading = true;
      axios
        .get("/api/inventory2/encargados", {
          params: { nro_dip: nro_dip },
        })
        .then((data) => {
          this.searchEncargadoLoading = false;
          this.searchEncargados = Object.values(data.data.data);
          this.searchEncargados.map(e=>{
              e.nro_dip = e.nro_dip.trim();
            	return e;
          });
          if(cb)
            cb(this.searchEncargados);
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
      //this.editForm.responsables= this.editForm.responsables.map(r => this.formatResponsable(this.responsables.filter(r2=> r===r2.nro_dip)[0]));
      //tratar de guardar los responsables como un json
      axios
        .post("/api/inventory2/saveChange", this.editForm)
        .then((data) => {
          this.$notify.success({
            title: "Cambios guardados",
            message: "Se realizo cambios al Inventario seleccionado exitosamente",
            duration: 0,
          });
          this.$router.push({
            name: "inventory2",
          });
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
      this.editForm.res_enc.push(addEncargado.nro_dip);
      this.res_enc.push(addEncargado);
      this.selectEncargado = null;
      this.showDialogEncargado = false;
    },
    saveAsset() {
      axios
        .post("/api/inventory2/save", this.editForm)
        .then((data) => {
          this.$notify.success({
        title: "Cambios guardados",
        message: "Se realizo cambios al Documento de inventario seleccionado exitosamente",
        duration: 0,
      });
      this.$router.push({
        name: "inventory2",
      });
        })
        .catch((err) => {
          console.log(err);
        });
    },
    initNewInventory() {
      this.$notify.info({
        title: "Edicion cancelada",
        message: "prueba de boton",
        duration: 0,
      });
      this.$router.push({
        name: "inventory2",
      });
    },
  },
};
</script>

<style lang = "css" scoped>
.el-row {
  padding-bottom: 10px;
}
.enc-select {
  width: calc(100% - 100px);
  margin-right: 15px; 
}
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