<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>usuarios</span>
        <el-button
          style="text-align: right; float: right"
          size="small"
          type="primary"
          icon="el-icon-plus"
          @click="initAddUser"
        >nuevo usuario</el-button>
      </div>
      <div style="margin-top: 15px;">
        <el-input
          placeholder="INSERTE UNA DESCRIPCION"
          v-model="writtenTextParameter"
          class="input-with-select"
        >
          <el-button slot="append" icon="el-icon-search"></el-button>
        </el-input>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="users" style="width: 100%">
          <el-table-column label="estado" width="150">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.activo }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="nodip" label="no" width="80"></el-table-column>
          <el-table-column prop="descripcion" label="descripcion" width="320"></el-table-column>
          <el-table-column prop="usuario" label="usuario"></el-table-column>
          <el-table-column align="right" width="280">
            <template slot-scope="scope">
              <el-button
                @click="initEditUser(scope.$index, scope.row)"
                type="primary"
                size="mini"
                plain
              >Editar</el-button>
              <el-button
                @click="initShowUser(scope.$index, scope.row)"
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
  name: "Usuarios",
  data() {
    return {
      selectParameter: "",
      parameters: [
        { attribute: "nombre de usuario", value: "nodip" },
        { attribute: "carnet de identidad", value: "descripcion" },
        { attribute: "descripcion", value: "usuario" },
        { attribute: "gestion", value: "gestion" },
      ],
      messages: {},
      users: [],
      pagination: { page: 1 },
      writtenTextParameter: "",
      loading: true,
    };
  },
  mounted() {
    let app = this;
    app.selectParameter = this.parameters[0].value;
    axios
      .post("/api/users", {
        descripcion: app.descripcion,
      })
      .then((response) => {
        app.loading = false;
        app.users = response.data.data;
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
        .post("/api/users", {
          descripcion: app.descripcion,
          page: page,
        })
        .then((response) => {
          app.loading = false;
          app.users = Object.values(response.data.data);
          app.pagination = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    initAddUser() {
      this.$router.push({ name: "adduser" });
    },
    initEditUser(index, row) {
      console.log(index, row);
      let personal = row.nro_dip;
      this.$router.push({ name: "edituser", params: { id: personal.trim() } });
    },
    initShowUser(index, row) {
      let personal = row.nro_dip;
      //router.push({ name: 'editperson', params: { userId: personal }})
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
