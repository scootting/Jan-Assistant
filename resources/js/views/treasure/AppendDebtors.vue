<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>deudores</span>
        <el-button
          style="text-align: right; float: right"
          size="small"
          type="primary"
          icon="el-icon-plus"
          @click="initAddDay"
          >nuevo dia</el-button
        >
      </div>
      <div>
        <el-table v-loading="loading" :data="days" style="width: 100%">
          <el-table-column prop="id_dia" label="dia" width="100">
            <template slot-scope="scope">
              <el-tag size="medium" type="danger">{{
                scope.row.id_dia
              }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column
            prop="fec_tra"
            label="fecha"
            width="100"
          ></el-table-column>
          <el-table-column
            prop="glosa"
            label="glosa"
            width="650"
          ></el-table-column>
          <el-table-column
            prop="importe"
            label="importe"
            width="100"
          ></el-table-column>
          <!--
          -->
          <el-table-column align="right" width="320">
            <template slot-scope="scope">
              <el-button
                @click="initAppendDebtors(scope.$index, scope.row)"
                type="primary"
                size="mini"
                plain
                >agregar deudores</el-button
              >
              <el-button
                @click="initDetailsDebtors(scope.$index, scope.row)"
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
          user: app.user.usuario,
          year: app.user.gestion,
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
      let app = this;
      app.loading = true;
      axios
        .post("/api/addSaleOfDay", {
          user: app.user.usuario,
          year: app.user.gestion,
        })
        .then((response) => {
          alert("el dia se creo con exito");
        })
        .catch((error) => {
          console.log(error);
        });
        app.loading = false;
        app.getDataPageSelected(1);
    },

    
    initDetailsDebtors(index, row) {
      let id = row.id_dia;
      axios({
        url: "/api/reportDetailStudents/" + row.id_dia,
        method: "GET",
        responseType: "blob",
      }).then((response) => {
        let blob = new Blob([response.data], {
          type: "application/pdf",
        });
        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        let url = window.URL.createObjectURL(blob);
        window.open(url);
      });
      alert("llegamos");
    },
    initAppendDebtors(index, row) {
      let id = row.id_dia;
      this.$router.push({
        name: "debtors",
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
