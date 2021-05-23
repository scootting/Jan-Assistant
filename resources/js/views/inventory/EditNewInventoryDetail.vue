<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Iniciar inventario de : {{ oficina.descripcion }}</span>
      </div>
      <br />
      <br />
      <el-form label-width="160px" :inline="true" size="normal"> </el-form>
      <div class="grid-content bg-purple">
        <el-row :gutter="20">
          <el-col :span="24" :offset="0">
            <el-form label-width="180px" :inline="true" size="small">
              <el-form-item label="Seleccion por:">
                <el-select
                  placeholder="Seleccionar"
                  v-model="filtro.tipo"
                  @change="filtro.values = []"
                >
                  <el-option
                    v-for="item in filtros"
                    :key="item.id"
                    :label="item.label"
                    :value="item.id"
                  >
                  </el-option>
                </el-select>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" size="small" 
                  >Cargar Responsables</el-button
                >
              </el-form-item>
              <el-form-item>
                <el-input
                  size="mini"
                  type="text"
                  class="input-with-select"
                ></el-input>
              </el-form-item>
            </el-form>
            <el-form label-width="180px" :inline="true" size="small">
              <el-form-item v-if="filtro.tipo != 'todo'" label="Seleccionar:">
                <selectSubUnidad
                  v-if="filtro.tipo == 'subUnidad'"
                  v-model="filtro.values"
                  multiple
                  :ofc-cod="oficina.cod_soa"
                />
                <selectCargos
                  v-if="filtro.tipo == 'cargo'"
                  v-model="filtro.values"
                  multiple
                  :ofc-cod="oficina.cod_soa"
                />
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
              v-model="NewInvent.date"
              type="datetime"
              placeholder="Selecionar Fecha"
            >
            </el-date-picker>
          </el-form-item>
          
          <el-form-item size="small" label="Encargados de Inventario">
            <el-select
              class="enc-select"
              v-model="NewInvent.encargados"
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
//Importar Componentes creados
import selectSubUnidad from "./components/selectSubUnidad";
import selectCargos from "./components/selectCargos";
export default {
  name: "InventoryDetail",
  components: {
    selectSubUnidad,
    selectCargos,
  },
  data() {
    return {
      reporte: {
        tipo: "general",
      },
      loading: false,
      user: this.$store.state.user,
      oficina: {},
      //encargados = los que haran el inventario ,  responsables = a quienes se asigna los activos
      NewInvent: {
        date: "",
        unidad: "",
        subUnidades: [],
        responsables: [],
        encargados: [],
        cargos: [],
      },
      subUnidades: [],
      cargos: [],
      responsables: [],
      encargados: [],
      searchEncargados: [],
      selectEncargado: null,
      searchEncargadoLoading: false,
      subUnidadesLoading: false,
      cargosLoading: false,
      responsablesLoading: false,
      showDialogEncargado: false,
      guardado: false,
      //filtro elegido para obtener los activos
      filtro: {
        tipo: "todo",
        values: [],
      },
      //tipos de filtros
      filtros: [
        {
          id: "todo",
          label: "TODO",
        },
        {
          id: "subUnidad",
          label: "SUB UNIDAD",
        },
        {
          id: "cargo",
          label: "CARGO",
        },
      ],
    };
  },
  mounted() {
    let cod_soa = this.$route.params.soa;
    axios
      .get("/api/inventory/show/" + cod_soa)
      .then((data) => {
        this.oficina = data.data;
      })
      .catch((err) => {});
  },
  methods: {
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
      this.NewInvent.encargados.push(addEncargado.nro_dip);
      this.encargados.push(addEncargado);
      this.selectEncargado = null;
      this.showDialogEncargado = false;
    },
    returnPage() {
      this.$notify.info({
        title: "Creación cancelada",
        message: "No se creo inventario nuevo",
        duration: 0,
      });
      this.$router.push({
        name: "newinventory",
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