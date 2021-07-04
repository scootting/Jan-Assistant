<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>historial de transacciones</span>
        <el-button
          style="text-align: right; float: right"
          size="small"
          type="primary"
          icon="el-icon-plus"
          @click="initSearchTransactions"
          >nueva persona</el-button
        >
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
            width="100"
          ></el-table-column>
          <el-table-column
            prop="cod_val"
            label="valorado"
            width="100"
          ></el-table-column>
          <el-table-column
            prop="des_val"
            label="descripcion"
            width="550"
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
          <el-table-column align="right" width="220">
            <template slot-scope="scope">
              <el-button
                @click="initEditTransaction(scope.$index, scope.row)"
                type="primary"
                size="mini"
                plain
                >Editar</el-button
              >
              <el-button
                @click="initCancelTransaction(scope.$index, scope.row)"
                type="danger"
                plain
                size="mini"
                >Anular</el-button
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
      messages: {},
      transactions: [],
      year: "2021",
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
    /*
    initAddPerson() {
      this.$router.push({
        name: "addperson",
      });
    },
    initEditPerson(index, row) {
      console.log(index, row);
      let personal = row.personal;
      this.$router.push({
        name: "editperson",
        params: {
          id: personal.trim(),
        },
      });
    },
    */
    initCancelTransaction(index, row) {
      let personal = row.id_tran;
      //router.push({ name: 'editperson', params: { userId: personal }})
    },
  },
};
</script>

<style scoped>
.el-input .el-select {
  width: 180px;
}
</style>
