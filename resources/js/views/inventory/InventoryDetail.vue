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
          >hola</el-button
        >
      </div>
      <br />
      <br />
      <el-form label-width="160px" :inline="true" size="normal">
        <el-form-item label="SUB-OFICINAS">
          <el-select
            placeholder="TODO"
            @change="selectSubOficina"
            v-model="subOficinaSelectId"
          >
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
        </el-form-item>
      </el-form>

      <br />

      <div>
        <el-table v-loading="loading" :data="activos" style="width: 100%">
          <el-table-column label="CODIGO">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.id }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="uni_med"
            label="UNIDAD"
          ></el-table-column>
          <el-table-column
            prop="des"
            label="DESCRIPCION"
          ></el-table-column>
          <el-table-column
            prop="estado"
            label="ESTADO"
          ></el-table-column>
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
      sub_oficinas: [],
      oficina: {},
      activos: [],
      pagination: {},
      subOficinaSelectId: 'TODOS',
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
          this.sub_oficinas.push({ id: -1, descripcion: "TODOS" });
          this.CargarActivos();
        })
        .catch((err) => {});
    },
    getActivosPaginate() {},
    selectSubOficina(id) {
      this.CargarActivos();
    },
    CargarActivos() {
      this.loading = true;
      let params = { page: this.pagination.page | 1 };
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
  },
};
</script>