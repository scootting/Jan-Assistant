<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>estudiantes nuevos</span>
        <el-button
          style="text-align: right; float: right"
          size="small"
          type="primary"
          icon="el-icon-plus"
          @click="initAddDay"
          >nuevo dia</el-button
        >
      </div>
      <div style="margin-top: 15px">
        <el-input
          placeholder="INSERTE UNA DESCRIPCION"
          v-model="writtenTextParameter"
          class="input-with-select"
        >
          <el-button slot="append" icon="el-icon-search" @click="test"></el-button>
        </el-input>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="days" style="width: 100%">
          <el-table-column prop="id_dia" label="dia" width="100">
            <template slot-scope="scope">
              <el-tag size="medium" type="danger">{{ scope.row.id_dia }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="fec_tra" label="fecha" width="100"></el-table-column>
          <el-table-column prop="glosa" label="glosa" width="650"></el-table-column>
          <el-table-column prop="importe" label="importe" width="100"></el-table-column>
          <el-table-column align="right" width="220">
            <template slot-scope="scope">
              <el-button
                @click="initDetailStudents(scope.$index, scope.row)"
                type="primary"
                size="mini"
                plain
                >detalle</el-button
              >
              <el-button
                @click="initSaleStudents(scope.$index, scope.row)"
                type="danger"
                plain
                size="mini"
                >venta</el-button
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
      days: [],
      pagination: {
        page: 1,
      },
      writtenTextParameter: "",
      loading: true,
    };
  },
  mounted() {
    let app = this;
    axios
      .post("/api/getSaleOfDaysByDescription", {
        description: app.writtenTextParameter,
        user: app.user.usuario,
        year: app.user.gestion,
      })
      .then((response) => {
        app.loading = false;
        app.days = response.data.data;
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
        .post("/api/getSaleOfDaysByDescription", {
          description: app.writtenTextParameter,
          user: app.user,
          year: app.year,
          page: page,
        })
        .then((response) => {
          app.loading = false;
          app.days = Object.values(response.data.data);
          app.pagination = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    initAddDay() {
      alert("el modulo esta a desicion del usuario");
    },

    initDetailStudents(index, row) {
      let id = row.id_dia;
      this.$router.push({
        name: "students",
        params: {
          id: id,
        },
      });
    },
    initSaleStudents(index, row) {
      let id = row.id_dia;
      alert(id);
      this.$router.push({
        name: "students",
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
