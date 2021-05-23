<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>{{ oficina.descripcion }}</span>
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
                <el-radio-group v-model="reporte.tipo" size="small" :span="20">
                  <el-radio-button label="general">General</el-radio-button>
                  <el-radio-button label="detallado">Detallado</el-radio-button>
                </el-radio-group>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" @click="cargarActivos" size="small"
                  >Cargar Activos</el-button
                >
              </el-form-item>
              <el-form-item>
                <el-button
                  type="primary"
                  @click="GenerarReporte"
                  icon="el-icon-plus"
                  size="small"
                  >Generar Reporte</el-button
                >
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
                <select-responsables
                  v-if="filtro.tipo == 'responsable'"
                  v-model="filtro.values"
                  multiple
                  :ofc-cod="oficina.cod_soa"
                />
              </el-form-item>
            </el-form>
          </el-col>
        </el-row>
      </div>
      <!--Inicio de generar la tabla-->
      <div>
        <el-table v-loading="loading" :data="activos" style="width: 100%">
          <el-table-column label="CODIGO" width="180">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.nro_doc }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="act_can"
            label="CANTIDAD"
            width="180"
          ></el-table-column>
          <el-table-column prop="uni_med" label="UNI. MED."></el-table-column>
          <el-table-column prop="act_des" label="DESCRIPCION"></el-table-column>
          <el-table-column
            v-if="tipoReporte === 'general'"
            prop="act_imp_bs"
            label="TOTAL"
            width="180"
          ></el-table-column>
          <el-table-column
            v-if="tipoReporte === 'detallado'"
            prop="act_imp_bs"
            label="PRECIO"
            width="180"
          ></el-table-column>
          <el-table-column
            v-if="tipoReporte === 'detallado'"
            label="ESTADO"
            width="180"
          >
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.estado }}</el-tag>
              </div>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination
          :page-size="pagination.per_page"
          layout="prev, pager, next"
          :current-page="pagination.current_page"
          :total="pagination.total"
          @current-change="getActivosPaginate"
        ></el-pagination>
      </div>
    </el-card>
  </div>
</template>

<script>
//Importar Componentes creados
import selectSubUnidad from "./components/selectSubUnidad";
import selectCargos from "./components/selectCargos";
import selectResponsables from "./components/selectResponsables";
export default {
  name: "InventoryDetail",
  components: {
    selectSubUnidad,
    selectCargos,
    selectResponsables,
  },
  data() {
    return {
      reporte: {
        tipo: "general",
      },
      loading: false,
      user: this.$store.state.user,
      oficina: {},
      activos: [],
      pagination: {
        page: 1,
      },
      respSelectCI: -1,
      //encargados: [],
      generar: 1,
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
        {
          id: "responsable",
          label: "RESPONSABLE",
        },
      ],
    };
  },
  mounted() {
    this.getActivosPaginate();
    let cod_soa = this.$route.params.soa;
    axios
      .get("/api/inventory/show/"+cod_soa)
      .then((data) => {
        this.oficina = data.data;
        this.getRespBySoa();
      })
      .catch((err) => {
        
      });
  },
  computed: {
    tipoReporte() {
      return this.reporte.tipo;
    },
  },
  methods: {
    //cargamos los activos con el encargado seleccionado
    getActivosPaginate(page) {
      this.pagination.page = page;
      this.selectEncargado();
    },
    selectEncargado(sub_ofc_cod) {
      this.respSelectCI = sub_ofc_cod;
      this.cargarActivos();
    },

    cargarActivos() {
      this.loading = true;
      let params = {
        page: this.pagination.page | 1,
        reporte: this.reporte.tipo,
        filtroTipo: this.filtro.tipo,
        filtroValor: this.filtro.values,
      };
      if (this.respSelectCI != -1) params.ci_resp = this.respSelectCI;
      axios
        .get("/api/inventory/activosByFilter/" + this.oficina.cod_soa, {
          params: params,
        })
        .then((data) => {
          this.loading = false;
          this.activos = Object.values(data.data.data);
          this.pagination = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    GenerarReporte() {
      axios({
        url: "/api/generarReporte/",
        params: {
          ofc_cod: this.oficina.cod_soa,
          reporte: this.reporte.tipo,
          filtroTipo: this.filtro.tipo,
          filtroValor: this.filtro.values,
        },
        method: "GET",
        responseType: "blob",
      }).then((response) => {
        console.log(response.data);
        console.log("1");
        let blob = new Blob([response.data], {
          type: "application/pdf",
        });
        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        console.log(blob);
        let url = window.URL.createObjectURL(blob);
        window.open(url);
        /*
        link.download = "test.pdf";
        link.click();
        */
      });
    },
    ReporteGeneral() {
      let ciResp;
      if (this.respSelectCI != -1) {
        ciResp = this.encargados.filter((e) => {
          if (e.sub_ofc_cod === this.respSelectCI) {
            return true;
          }
          return false;
        })[0].ci_resp;
      } else {
        ciResp = -1;
      }
      axios({
        url: "/api/inventarioGeneral/",
        params: {
          ofc_cod: this.oficina.cod_soa,
          resp: ciResp,
        },
        method: "GET",
        responseType: "blob",
      }).then((response) => {
        console.log(response.data);
        console.log("1");
        let blob = new Blob([response.data], {
          type: "application/pdf",
        });
        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        console.log(blob);
        let url = window.URL.createObjectURL(blob);
        window.open(url);
        /*
        link.download = "test.pdf";
        link.click();
        */
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