<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>historial de transacciones</span>
      </div>
      <div style="margin-top: 15px">
        <el-input
          placeholder="INSERTE UNA DESCRIPCION"
          v-model="writtenTextParameter"
          class="input-with-select"
        >
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="initSearchTransactions"
          ></el-button>
        </el-input>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="transactions" style="width: 100%">
          <el-table-column
            prop="id_tran"
            label="id"
            width="80"
          ></el-table-column>
          <el-table-column
            prop="cod_val"
            label="codigo"
            width="80"
          ></el-table-column>
          <el-table-column
            prop="des_val"
            label="descripcion"
            width="500"
          ></el-table-column>
          <el-table-column
            prop="ci_per"
            label="CARNET"
            width="100"
          ></el-table-column>
          <el-table-column
            prop="des_per"
            label="apellidos y nombres"
            width="300"
          ></el-table-column>
          <el-table-column align="right" width="300" label="anulacion">
            <template slot-scope="scope">
              <el-button
                :disabled="transactions[scope.$index].tip_tra == 9"
                @click="initEditTransaction(scope.$index, scope.row)"
                type="primary"
                size="mini"
                plain
                >con papeleta</el-button
              >
              <el-button
                :disabled="transactions[scope.$index].tip_tra == 9"
                @click="initCancelTransaction(scope.$index, scope.row)"
                type="danger"
                plain
                size="mini"
                >sin papeleta</el-button
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
  name: "Transacciones",
  data() {
    return {
      transactions: [],
      user: this.$store.state.user,
      year: '',
      pagination: {
        page: 1,
      },
      writtenTextParameter: "",
      loading: true,
    };
  },
  mounted() {
    let app = this;
    this.year = app.user.gestion;
    axios
      .post("/api/getAllTransactionsByYear", {
        description: app.writtenTextParameter,
        year: app.year,
      })
      .then((response) => {
        app.loading = false;
        app.transactions = response.data.data;
        console.log(response.data);
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
        .post("/api/getAllTransactionsByYear", {
          description: app.writtenTextParameter,
          year: app.year,
          page: page,
        })
        .then((response) => {
          app.loading = false;
          app.transactions = Object.values(response.data.data);
          app.pagination = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    initSearchTransactions() {
      let app = this;
      app.loading = true;
      axios
        .post("/api/getAllTransactionsByYear", {
          description: app.writtenTextParameter,
          year: app.year,
        })
        .then((response) => {
          app.loading = false;
          app.transactions = response.data.data;
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
    initEditTransaction(index, row) {
      let app = this;
      axios
        .post("/api/cancelTransactionById", {
          transaccion: row,
          gestion: app.user.gestion,
          usuario: app.user.usuario,
          tipo: 1,
        })
        .then((response) => {
          console.log(response.data);
          alert("Se ha anulado la transaccion seleccionada");
        })
        .catch((error) => {
          this.error = error;
          this.$notify.error({
            title: "Error",
            message: this.error.message,
          });
        });
    },
    initCancelTransaction(index, row) {
      let app = this;
      axios
        .post("/api/cancelTransactionById", {
          transaccion: row,
          gestion: app.user.gestion,
          usuario: app.user.usuario,
          tipo: 0,
        })
        .then((response) => {
          console.log(response.data);
          alert("Se ha anulado la transaccion seleccionada");
        })
        .catch((error) => {
          this.error = error;
          this.$notify.error({
            title: "Error",
            message: this.error.message,
          });
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
