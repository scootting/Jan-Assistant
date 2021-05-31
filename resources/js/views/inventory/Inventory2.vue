
<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Inventario</span>
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
          <el-table-column align="right-center" width="300" label="Operaciones">
            <template slot-scope="scope">
              <el-button
                @click="editInventory(scope.$index, scope.row)"
                type="primary"
                plain
                size="mini"
                >Editar</el-button
              > 
              <el-button
                @click="listActive(scope.row.id)"
                type="primary"
                plain
                size="mini"
                :disabled="verificado"
                >Ver lista </el-button
              >
              <el-button
                @click="printListActive(scope.row.id)"
                type="primary"
                plain
                size="mini"
                :disabled="!verificado"
                >Imprimir </el-button
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
      verificado:false,
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
    editInventory(index, row) {
      this.$router.push({
        name: "editinventory2",
        params: {
          id: row.id,
        },
      });
    },
    listActive(no_cod){
      this.$router.push({
        name: "inventory2detail",
        params: {
          no_cod: no_cod,
        }
      });
    },
    printListActive(no_cod){
      alert("Button to Print New Invent");
    },
    test() {
      alert("bienvenido al modulo");
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped></style>