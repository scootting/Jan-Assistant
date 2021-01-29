
<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Inventario</span>
        <el-button
          style="text-align: right; float: right"
          size="small"
          type="primary"
          icon="el-icon-plus"
          @click="newInventory"
          >Nuevo Inventario</el-button
        >
      </div>

      <div style="margin-top: 15px">
        <el-input
          placeholder="INSERTE UNA DESCRIPCION"
          v-model="writtenTextParameter"
          class="input-with-select"
          @keyup.enter.native="getOffices"
        >
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="getInventories"
          ></el-button>
        </el-input>
      </div>
      <br />
      <div>
        <el-table border stripe>
          <el-table-column
            v-for="col in columns"
            :prop="col.id"
            :key="col.id"
            :label="col.label"
            :width="col.width"
          >
          </el-table-column>
        </el-table>
        <el-table v-loading="loading" :data="data" style="width: 100%">
          <el-table-column label="Nro Doc">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.no_cod }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="ofc_cod" label="Cod_soa"> </el-table-column>
          <el-table-column prop="descripcion" label="Unidad"></el-table-column>
          <el-table-column label="Estado">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.estado }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column align="right-center" width="220" label="Operaciones">
            <template slot-scope="scope">
              <el-button
                @click="initShowInventory(scope.$index, scope.row)"
                type="primary"
                plain
                size="mini"
                >Editar</el-button
              > <br>
              <el-button
                @click="initShowInventory(scope.$index, scope.row)"
                type="primary"
                plain
                size="mini"
                >Ver Detalles</el-button
              >
            </template>
          </el-table-column>
        </el-table>
        <el-pagination
          :page-size="pagination.per_page"
          layout="prev, pager, next"
          :current-page="pagination.current_page"
          :total="pagination.total"
          @current-change="getOfficesPaginate"
        ></el-pagination>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "Inventarios2",
  data() {
    return {
      loading: false,
      user: this.$store.state.user,
      messages: {},
      data: [],
      pagination: {
        page: 1,
      },
      writtenTextParameter: "",
    };
  },
  mounted() {
    this.getInventories();
  },
  methods: {
    getInventories() {
      this.loading = true;
      axios
        .get("/api/inventory2/" + 2020, {
          params: {
            page: this.pagination.page,
            descripcion: this.writtenTextParameter.toUpperCase(),
          },
        })
        .then((data) => {
          this.loading = false;
          this.data = Object.values(data.data.data);
          this.pagination = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getOfficesPaginate(page) {
      this.pagination.page = page;
      this.getInventories();
    },
    initShowInventory(index, row) {
      this.$router.push({
        name: "editInventory",
        params: {
          soa: row.cod_soa,
        },
      });
    },
    newInventory() {
      this.$router.push({ name: "newinventory" });
    },

    test() {
      alert("bienvenido al modulo");
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped></style>