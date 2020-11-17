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
        <!--
            <el-form-item label="SUB-OFICINAS">
                <el-select placeholder="TODO" v-model="subOficinaSelectId">
                    <el-option v-for="item in sub_oficinas" :key="item.id" :label="item.descripcion" :value="item.id">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="CargarActivos">Cargar Activos</el-button>
            </el-form-item>
            <br />
            <br />-->
        <el-form label-width="160px" :inline="false" size="normal">
          <el-form-item label="SUB-UNIDADES:">
            <el-dropdown
              trigger="click"
              size="default"
              split-button="true"
              type="primary"
              @command="selectSubOficina"
            >
              Sub-Oficinas
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item
                  v-for="item in sub_oficinas"
                  :key="item.id"
                  :command="item.id"
                >
                  {{ item.descripcion }}
                </el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </el-form-item>
        </el-form>
      </el-form>

      <br />

      <br />
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
      subOficinaSelectId: "TODOS",
    };
  },
  mounted() {
    let cod_soa = this.$route.params.soa;
    axios
      .get("/api/inventory/show/" + cod_soa)
      .then((data) => {
        this.oficina = data.data;
        this.getSubOficinas();
      })
      .catch((err) => {});
  },
  methods: {
    getSubOficinas() {
      axios
        .get("/api/inventory/sub_offices/" + this.oficina.cod_soa)
        .then((data) => {
          this.subOficinaSelectId = -1;
          this.sub_oficinas = data.data;
          this.sub_oficinas.push({
            id: -1,
            descripcion: "TODOS",
          });
        })
        .catch((err) => {});
    },
    getActivosPaginate(page) {
      this.pagination.page = page;
      this.selectSubOficina();
    },
    selectSubOficina(id) {
      this.subOficinaSelectId = id;
      this.CargarActivos();
    },
    CargarActivos() {
      this.loading = true;
      let params = {
        page: this.pagination.page | 1,
      };
      if (this.subOficinaSelectId != -1)
        params.idSubOffice = this.subOficinaSelectId;
      axios
        .get("/api/inventory/activos/" + this.oficina.cod_soa, {
          params: params,
        })
        .then((data) => {
          this.loading = false;
          this.activos = Object.values(data.data.data);
          this.pagination = data.data;
        })
        .catch((err) => {});
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
  },
};
</script>
