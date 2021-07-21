<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>activos fijos</span>
        <el-button
          style="text-align: right; float: right"
          size="small"
          type="primary"
          icon="el-icon-plus"
          @click="test"
          >imprimir</el-button
        >
      </div>
      <div>
        <el-table
          v-loading="loading"
          :data="fixedAssetsDocument"
          style="width: 100%"
          border
        >
          <el-table-column prop="nro_doc" label="numero" :min-width="20">
            <template slot-scope="scope">
              <el-tag size="medium" type="danger">{{
                scope.row.nro_doc
              }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column
            prop="fecha"
            label="fecha"
            :min-width="20"
          ></el-table-column>
          <el-table-column
            prop="responsable"
            label="responsable"
            :min-width="40"
          ></el-table-column>
          <!--
          -->
          <el-table-column align="right" :min-width="20">
            <template slot-scope="scope">
              <el-button
                @click="initSelectedFixedAssetsByDocument(scope.$index, scope.row)"
                type="success"
                plain
                size="mini"
                >ver detalles</el-button
              >
            </template>
          </el-table-column>
        </el-table>
        <el-pagination
          :page-size="pagination.per_page"
          layout="prev, pager, next"
          :current-page="pagination.current_page"
          :total="pagination.total"
          @current-change="getDataPageSelected"
        ></el-pagination>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "Dias",
  data() {
    return {
      user: this.$store.state.user,
      messages: {},
      fixedAssetsDocument: [],
      pagination: {
        page: 1,
      },
      loading: true,
    };
  },
  mounted() {
    let app = this;
    axios
      .post("/api/documentFixedAssets", {
        user: app.user.usuario,
        year: app.user.gestion,
      })
      .then((response) => {
        app.loading = false;
        app.fixedAssetsDocument = response.data.data;
        app.pagination = response.data;
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
    test() {
      alert("bienvenido al modulo");
    },
    getDataPageSelected(page) {
      let app = this;
      app.loading = true;
      axios
        .post("/api/documentFixedAssets", {
          user: app.user.usuario,
          year: app.user.gestion,
          page: page,
        })
        .then((response) => {
          app.loading = false;
          app.fixedAssetsDocument = Object.values(response.data.data);
          app.pagination = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    initSelectedFixedAssetsByDocument(index, row) {
      let id = row.nro_doc;
      this.$router.push({
        name: "selectedFixedAssetsByDocument",
        params: {
          id: id,
        },
      });
    },
  },
};
</script>

<style scoped>
.el-input .el-select {
  width: 180px;
}
</style>
