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
                v-model="NewInvent.encargados"
                filterable
                remote
                multiple
                reserve-keyword
                placeholder="Seleccione Encargados para Inventario"
                :remote-method="getEncargados"
                maxlength="30"
                style="width:100%"
                :loading = "encargadosLoading"
              >
                <el-option
                  v-for="item in encargados"
                  :key="item.nro_dip"
                  :label="item.paterno +' '+ item.nombres"
                  :value="item.nro_dip"
                >
                </el-option>
              </el-select>
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
                style="width:100%"
                @change="onChangeUnidad"
                :loading = "unidadLoading"
              >
                <el-option
                  v-for="item in unidades"
                  :key="item.cod_soa"
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
                style="width:100%"
                @change="onChangeSubUnidades"
                :loading = "subUnidadLoading"
              >
                <el-option
                  v-for="item in subUnidades"
                  :key="item.id"
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
                style="width:100%"
                @change="onChangeCargos"
                :loading = "cargosLoading"
              >
                <el-option
                  v-for="item in cargos"
                  :key="item.id"
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
                style="width:100%"
                :loading = "responsablesLoading"
              >
                <el-option
                  v-for="item in responsables"
                  :key="item.nro_dip"
                  :label="item.nombres  + item.paterno"
                  :value="item.nro_dip"
                >
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="" size="small">
              <el-row type="flex" justify="end">
                <el-button type="prymary" size="default">Guardar</el-button>
                <el-button type="default" size="default">Realizar Inventario</el-button>
              </el-row>
            </el-form-item>
          </el-form>
        </el-row>
      </div>
    </el-card>
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
        subUnidades: "",
        cargos: "",
        responsables:"",
        encargados:"",
      },
      unidades: [],
      subUnidades: [],
      cargos: [],
      responsables: [],
      encargados: [],
      encargadosLoading: false,
      subUnidadesLoading: false,
      unidadesLoading: false,
      cargosLoading: false,
      responsablesLoading: false,
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
    onChangeSubUnidades(subUnidades){
      let cod_soa = this.NewInvent.unidad;
      this.getCargosResp(cod_soa,subUnidades);
    },
    onChangeCargos(cargos){
      let cod_soa = this.NewInvent.unidad;
      this.getResponsables(cod_soa,cargos);
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
          this.getCargosResp( this.NewInvent.unidad,this.NewInvent.subUnidades);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getCargosResp(cod_soa,subUnidades){
      this.cargosLoading = true;
      this.subUnidadesLoading = true;
      axios
      .get("/api/inventory2/cargos", {
          params: {
            cod_soa: cod_soa,
            sub_unidades: subUnidades
          },
        })
        .then((data)=>{
          this.cargosLoading = false;
          this.subUnidadesLoading = false;
          this.cargos = data.data;
          this.NewInvent.cargos = this.cargos.map((car)=>car.id);
          this.getResponsables(this.NewInvent.unidad,this.NewInvent.cargos);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getResponsables(cod_soa,cargos){
      this.cargosLoading = true;
      this.responsablesLoading = true;
       axios
      .get("/api/inventory2/responsables", {
          params: {
            cod_soa: cod_soa,
            cargos: cargos
          },
        })
        .then((data)=>{
          this.cargosLoading = false;
          this.responsablesLoading = false;
          this.responsables = data.data;
          this.NewInvent.responsables = this.responsables.map((resp)=>resp.nro_dip);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getEncargados(nro_dip){
      this.encargadosLoading = true;
       axios
      .get("/api/inventory2/encargados", {
          params: { nro_dip:nro_dip },
        })
        .then((data)=>{
          this.encargadosLoading = false;
          this.encargados = Object.values(data.data.data);
          console.log(data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
};
</script>

<style lang = "css" scoped>
.el-row {
  padding-bottom: 10px;
}
</style>