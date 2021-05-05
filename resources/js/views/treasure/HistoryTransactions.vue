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
          @click="initAddPerson"
        >imprimir historial</el-button>
      </div>
      <div style="margin-top: 15px;">
        <el-input
          placeholder="INSERTAR EL CARNET DE IDENTIDAD"
          v-model="writtenTextParameter"
          class="input-with-select"
        >
          <el-button slot="append" icon="el-icon-search" @click="initSearchPerson"></el-button>
        </el-input>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="people" style="width: 100%">
          <el-table-column prop="personal" label="CARNET"></el-table-column>
          <el-table-column prop="paterno" label="PATERNO"></el-table-column>
          <el-table-column prop="materno" label="MATERNO"></el-table-column>
          <el-table-column prop="nombres" label="NOMBRES" width="280"></el-table-column>
          <el-table-column align="right" width="220">
            <template slot-scope="scope">
              <el-button
                @click="initEditPerson(scope.$index, scope.row)"
                type="primary"
                size="mini"
                plain
              >Editar</el-button>
              <el-button
                @click="initShowPerson(scope.$index, scope.row)"
                type="danger"
                plain
                size="mini"
              >Mostrar</el-button>
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
  name: "Personas",
  data() {
    return {
      messages: {},
      people: [],
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
      .post("/api/persons", {
        descripcion: app.writtenTextParameter,
      })
      .then((response) => {
        app.loading = false;
        app.people = response.data.data;
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
        .post("/api/persons", {
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
        });
    },
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
    initSearchPerson() {
      let app = this;
      app.loading = true;
      axios
        .post("/api/persons", {
          descripcion: app.writtenTextParameter,
        })
        .then((response) => {
          app.loading = false;
          app.people = response.data.data;
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
    initShowPerson(index, row) {
      let personal = row.nro_dip;
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
