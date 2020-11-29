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
        />
      </div>
      <div>
        <el-row>
          <el-col :span="24"></el-col>
          <el-form label-width="160px" :inline="false" size="small">
            <el-form-item size="small" label="Encargados">
              <el-select
                v-model="NewInvent.encargados"
                filterable
                remote
                reserve-keyword
                placeholder="Seleccione encargados"
                maxlength="30"
              >
                <el-option
                  v-for="item in encargados"
                  :key="item.descripcion"
                  :label="item.descripcion"
                  :value="item.descripcion"
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
                @change="onChangeUnidad"
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
                v-model="NewInvent.cargo"
                filterable
                remote
                multiple
                reserve-keyword
                placeholder="Cargos"
                :remote-method="remoteMethod"
                maxlength="30"
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
                v-model="NewInvent.responsable"
                filterable
                remote
                multiple
                reserve-keyword
                placeholder="Responsables"
                :remote-method="remoteMethod"
                maxlength="30"
              >
                <el-option
                  v-for="item in responsables"
                  :key="item.ci_resp"
                  :label="item.ci_resp"
                  :value="item.ci_resp"
                >
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="" size="small">
              <el-row type="flex" justify="end">
                <el-button type="success" size="default">Guardar</el-button>
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
      },
      unidades: [],
      subUnidades: [],
      cargos: [],
      responsables: [],
      encargados: [],
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
    getSubUnidades(cod_soa) {
      axios
        .get("/api/inventory2/sub_unidad", {
          params: { cod_soa: cod_soa },
        })
        .then((data) => {
          this.subUnidades = data.data;
          this.NewInvent.subUnidades = this.subUnidades.map((su) => su.id);
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