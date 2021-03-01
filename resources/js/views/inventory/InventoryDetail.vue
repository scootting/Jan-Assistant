<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>{{ oficina.descripcion }}</span>
        <el-button
          style="text-align: right; float: right"
          size="small"
          type="primary"
          icon="el-icon-plus"
          @click="loadReportAssets"
          >imprimir reporte</el-button
        >
      </div>
      <br />
      <br />
      <el-form label-width="160px" :inline="true" size="normal">
        <!--  <el-form-item label="SUB-OFICINAS">
          <el-select placeholder="TODO" v-model="subOficinaSelectId">
            <el-option
              v-for="item in sub_oficinas"
              :key="item.id"
              :label="item.descripcion"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="CargarActivos"
            >Cargar Activos</el-button
          >
        </el-form-item> -->
      </el-form>
      <div class="grid-content bg-purple">
        <el-row :gutter="20">
          <el-col :span="10" :offset="0">
            <el-form
              label-width="100px"
              :inline="false"
              size="small"
            >
              <el-form-item label="oficina:">
                <el-input
                  v-model="oficina.descripcion"
                  placeholder="oficina seleccionada"
                  size="small"
                  disabled
                  style="width: 210px"
                ></el-input>
              </el-form-item>
              <el-form-item label="Encargados:">
                <el-select placeholder="Encargados" v-model="respSelectCI">
                  <el-option
                    v-for="item in encargados"
                    :key="item.sub_ofc_cod"
                    :label="
                      item.nombres + ' ' + item.paterno + ' ' + item.materno
                    "
                    :value="item.sub_ofc_cod"
                  >
                  </el-option>
                </el-select>
                <el-button type="primary" @click="cargarActivos"
                  >Cargar Activos</el-button
                >
              </el-form-item>
            </el-form>
          </el-col>
          <el-col :span="17" :offset="6">
            <el-form
              label-width="80px"
              :inline="false"
              size="normal"
            >
              <el-form-item label="">
                <el-button
                  style="text-align: right; float: right"
                  size="small"
                  type="primary"
                  icon="el-icon-plus"
                  @click="ReporteDetalle"
                  >Reporte Detallado</el-button
                >
                <el-button
                  style="text-align: right; float: right"
                  size="small"
                  type="primary"
                  icon="el-icon-plus"
                  @click="ReporteGeneral"
                  >Reporte General</el-button
                >
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
                <el-tag size="medium">{{ scope.row.id }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="uni_med"
            label="MEDIDA"
            width="180"
          ></el-table-column>
          <el-table-column prop="des" label="DESCRIPCION"></el-table-column>
          <el-table-column label="ESTADO" width="180">
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
export default {
  name: "InventoryDetail",
  data() {
    return {
      loading: false,
      user: this.$store.state.user,
      sub_oficinas: [],
      oficina: {},
      activos: [],
      pagination: {
        page: 1,
      },
      respSelectCI: -1,
      encargados: [],
      generar: 1,
    };
  },
  mounted() {
    this.getActivosPaginate();
    let cod_soa = this.$route.params.soa;
    axios
      .get("/api/inventory/show/" + cod_soa)
      .then((data) => {
        this.oficina = data.data;
        this.getRespBySoa();
      })
      .catch((err) => {});
  },
  methods: {
    //obtener a los encargados que estan dentro de la oficina.
    getRespBySoa() {
      axios
        .get("/api/inventory/cargos/" + this.oficina.cod_soa)
        .then((data) => {
          this.respSelectCI = -1;
          this.encargados = data.data;
          this.encargados.push({
            nro_dip: -1,
            nombres: "Todos",
            paterno: "",
            materno: "",
            sub_ofc_cod: -1,
          });
        })
        .catch((err) => {});
    },
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
      };
      if (this.respSelectCI != -1) params.ci_resp = this.respSelectCI;
      axios
        .get("/api/inventory/activosByResp/" + this.oficina.cod_soa, {
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
    loadReportAssets() {
      axios({
        url: "/api/descargando/" + this.oficina.cod_soa,
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
    ReporteDetalle() {
    let ciResp = null;
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
        url: "/api/inventarioDetalle/",
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
    ReporteGeneral() {
      let ciResp 
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