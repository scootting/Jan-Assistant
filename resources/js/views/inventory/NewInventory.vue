<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Nuevo Inventario</span>
        <input
          style="text-align: right; float: right"
          type="text"
          disabled
          v-bind:value="No_Doc"
          placeholder="Nro de documento"
        />
      </div>
      <div>
        <el-row>
          <el-form label-width="160px" :inline="false" size="small">
            <el-form-item size="small" label="Encargados">
              <el-select
                class="enc-select"
                v-model="NewInvent.encargados"
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
              <el-button type="primary" size="mini" @click="showDialogEncargado = true"
                >Buscar</el-button
              >
            </el-form-item>

            <el-form-item label="Unidad " size="small">
              <el-select
                v-model="NewInvent.unidad"
                filterable
                remote
                reserve-keyword
                placeholder="Seleccione una Unidad"
                :remote-method="remoteMethod"
                maxlength="30"
                style="width: 100%"
                @change="onChangeUnidad"
                :loading="unidadLoading"
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
                v-model="NewInvent.subUnidades"
                filterable
                remote
                multiple
                reserve-keyword
                placeholder="Sub Oficina"
                :remote-method="getSubUnidades"
                maxlength="30"
                style="width: 100%"
                @change="onChangeSubUnidades"
                :loading="subUnidadesLoading"
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
                v-model="NewInvent.cargos"
                filterable
                remote
                multiple
                reserve-keyword
                placeholder="Cargos"
                :remote-method="getCargosResp"
                maxlength="30"
                style="width: 100%"
                @change="onChangeCargos"
                :loading="cargosLoading"
              >
                <el-option
                  v-for="(item, index) in cargos"
                  :key="index"
                  :label="item.descripcion"
                  :value="item.id"
                >
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Responsable" size="small">
              <el-select
                v-model="NewInvent.responsables"
                filterable
                remote
                multiple
                reserve-keyword
                placeholder="Responsables"
                :remote-method="getResponsables"
                maxlength="30"
                style="width: 100%"
                :loading="responsablesLoading"
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
            <el-form-item label="" size="small">
              <el-row type="flex" justify="end">
                <el-button type="prymary" size="default" @click="saveInventory"
                  >Guardar</el-button
                >
                <el-button type="default" size="default"
                  >Realizar Inventario</el-button
                >
              </el-row>
            </el-form-item>
          </el-form>
        </el-row>
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
        <el-button type="primary" @click="onConfirmDialog">CONFIRMAR</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: "newInventory",
  data() {
    return {
      No_Doc: null,
      NewInvent: {
        unidad: "",
        subUnidades: [],
        cargos: [],
        responsables: [],
        encargados: [],
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
  mounted() {
    this.remoteMethod("");
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
      this.getSubUnidades(cod_soa);
    },
    onChangeSubUnidades(subUnidades) {
      let cod_soa = this.NewInvent.unidad;
      this.getCargosResp(cod_soa,subUnidades); //subUnidades
      this.getResponsables(cod_soa, cargos);
      
    },
    onChangeCargos(cargos) {
      let cod_soa = this.NewInvent.unidad;
      this.getResponsables(cod_soa, cargos);
    },
    getSubUnidades(cod_soa) {
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
          this.NewInvent.subUnidades = this.subUnidades.map((su) => su.id);
          this.getCargosResp(this.NewInvent.unidad, this.NewInvent.subUnidades);
          
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getCargosResp(cod_soa,subUnidades) {
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
          this.NewInvent.cargos = this.cargos.map((car) => car.id);
          this.getResponsables(this.NewInvent.unidad, this.NewInvent.cargos);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getResponsables(cod_soa, cargos) {
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
          this.NewInvent.responsables = this.responsables.map(
            (resp) => resp.nro_dip
          );
        })
        .catch((err) => {
          console.log(err);
        });
    },
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
      //this.NewInvent.responsables= this.NewInvent.responsables.map(r => this.formatResponsable(this.responsables.filter(r2=> r===r2.nro_dip)[0]));
      //tratar de guardar los responsables como un json
      axios
        .post("/api/inventory2/save", this.NewInvent)
        .then((data) => {
          this.$message({
            message: "Inventario creado exitosamente",
            type: "success",
            duration: 5000,
            showClose: true,
          });
          this.route.push({ name: "inventory2" });
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
      if(!this.selectEncargado){
        this.$message({
          message: 'NO selecciono ningun encargado',
          type:'warning',
          showClose: true,
          duaration: 5000,
        });
        return;
      }
      let addEncargado = this.searchEncargados.filter(e=>{
        return e.nro_dip === this.selectEncargado;
      })[0];
      this.NewInvent.encargados.push(addEncargado.nro_dip);
      this.encargados.push(addEncargado);
      this.selectEncargado = null;
      this.showDialogEncargado = false;
    },
  },
};
</script>

<style lang = "css" scoped>
.el-row {
  padding-bottom: 10px;
}
.enc-select{
  width: calc(100% - 100px);
  margin-right: 15px;
}
</style>