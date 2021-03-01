<template>
  <div >
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Unidades</span>
        <el-button style="float: right; padding: 3px 0" type="text"
          >Ayuda</el-button
        >
      </div>

      <div
        style="margin-top: 15px">
        <el-input
          placeholder="INSERTE UNA DESCRIPCION"
          v-model="writtenTextParameter"
          class="input-with-select"
          @keyup.enter.native="getOffices"
        >
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="getOffices"
          ></el-button>
        </el-input>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="data" style="width: 100%">
          <el-table-column label="CODIGO SOA">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.cod_soa }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="descripcion"
            label="DESCRIPCION"
          ></el-table-column>
          <el-table-column align="right" width="220">
            <template slot-scope="scope">
              <el-button
                @click="initShowInventory(scope.$index, scope.row)"
                type="primary"
                plain
                size="mini"
                >Inventario</el-button
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
  name: "Inventarios",
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
    this.getOffices();
  },
  methods: {
    getOffices() {
      this.loading = true;
      axios
        .get("/api/inventory/" + 2020, {
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
      this.getOffices();
    },
    initShowInventory(index, row) {
      this.$router.push({
        name: "inventorydetail",
        params: {
          soa: row.cod_soa,
        },
      });
    },

    test() {
      alert("bienvenido al modulo");
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped></style>
