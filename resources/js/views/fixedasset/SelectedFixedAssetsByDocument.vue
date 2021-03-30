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
          @click="initAddSolvency"
          >imprimir</el-button
        >          
      </div>
      <br />
      <div>
          <!--
        <el-table v-loading="loading" :data="listSolvency" style="width: 100%">
          <el-table-column prop="id" label="ID"></el-table-column>
          <el-table-column prop="fecha" label="FECHA"></el-table-column>
          <el-table-column prop="operaciones" label="OPERACION"></el-table-column>
          <el-table-column
            prop="nombres"
            label="NOMBRES"
            width="280"
          ></el-table-column>
          <el-table-column align="right" width="220">
            <template slot-scope="scope">
              <el-button
                @click="initChangeStateSolvency(scope.$index, scope.row)"
                type="primary"
                size="mini"
                plain
                >Editar</el-button
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
        -->
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "Personas",
  data() {
    return {
      messages: {},
      dataFixedAssets: [],
      pagination: {
        page: 1,
      },
      loading: true,
    };
  },
  mounted() {
    let app = this;
    console.log();
    /*
    axios
      .post("/api/solvencies", {
        descripcion: app.writtenTextParameter,
      })
      .then((response) => {
        app.loading = false;
        app.solvencies = response.data.data;
        app.pagination = response.data;
      })
      .catch((error) => {
        this.error = error;
        this.$notify.error({
          title: "Error",
          message: this.error.message,
        });
      });*/
  },
  methods: {
    getDataPageSelected(page) {
      let app = this;
      app.loading = true;
      /*
      axios
        .post("/api/solvencies", {
          descripcion: app.writtenTextParameter,
          page: page,
        })
        .then((response) => {
          app.loading = false;
          app.people = Object.values(response.data.data);
          app.pagination = response.data;
        })
        .catch((error) => {
          console.log(error);
        });*/
    },
    initAddSolvency() {
    },
    initChangeStateSolvency(index, row) {
      console.log(index, row);
    },
  },
};
</script>

<style scoped>
.el-input .el-select {
  width: 180px;
}
</style>
