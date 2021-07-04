<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>activos fijos</span>
      </div>
      <div
        style="margin-top: 15px">
        <el-input
          placeholder="INSERTE UNA DESCRIPCION"
          v-model="writtenTextParameter"
          class="input-with-select"
          @keyup.enter.native="getListDoc"
        >
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="getListDoc"
          ></el-button>
        </el-input>
      </div>
      <br />
      <div>
        <el-table
          v-loading="loading"
          :data="documentList"
          style="width: 100%"
        >
          <el-table-column prop="nro_doc" label="numero" width="100">
            <template slot-scope="scope">
              <el-tag size="medium" type="danger">{{
                scope.row.nro_doc
              }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column
            prop="des"
            label="descripcion"
            width="850"
          ></el-table-column>
          <el-table-column align="right" width="320">
            <template slot-scope="scope">
              <el-button
                @click="initSelectedActiveByDocument(scope.$index, scope.row)"
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
          @current-change="getDocPaginate"
        ></el-pagination>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "DocumentQR",
  data() {
    return {
      user: this.$store.state.user,
      messages: {},
      documentList: [],
      writtenTextParameter: "",
      pagination: {
        page: 1,
      },
      loading: true,
    };
  },
  mounted() {
    this.getListDoc()
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    getListDoc() {
      this.loading = true;
      axios
        .get("/api/listNroDoc", {
        params:{
            descripcion : this.writtenTextParameter.toUpperCase(),
            page: this.pagination.page,
        },
        })
        .then((data) => {
          this.loading = false;
          this.documentList = Object.values(data.data.data);
          this.pagination = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getDocPaginate(page) {
      this.pagination.page = page;
      this.getListDoc("");
    },
    initSelectedActiveByDocument(index, row) {
      let id = row.nro_doc;
      this.$router.push({
        name: "selectactivebydocument",
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