<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span
          >Lista de activos para la realizacion del inventario
          {{ cod_doc }}</span
        >
        <el-button style="float: right; padding: 3px 0" type="text"
          >Ayuda</el-button
        >
      </div>
      <div>
        <el-form
          :model="doc_inv"
          label-width="160px"
          :inline="false"
          size="mini"
        >
          <el-form-item label="Oficina:">
            <el-input v-model="doc_inv.oficina.descripcion" disabled></el-input>
          </el-form-item>
          <el-form-item label="Sub Oficinas:">
            <el-tag
              v-for="sub_oficina in doc_inv.sub_oficinas"
              :key="sub_oficina.id"
              type="success"
              size="normal"
              effect="dark"
              >{{ sub_oficina.descripcion }}</el-tag
            >
          </el-form-item>
          <el-form-item label="Encargados de inventario:">
            <el-tag
              v-for="encargado in doc_inv.encargados"
              :key="encargado.nro_dip"
              type="default"
              size="normal"
              effect="dark"
              >{{ formatResponsable(encargado).responsable }}</el-tag
            >
          </el-form-item>
        </el-form>
      </div>
      <div>
        <el-table v-loading="loading" :data="data" style="width: 100%">
          <el-table-column label="Identificador" width="140">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="small">{{ scope.row.id }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="des"
            label="DESCRIPCION"
            width="330"
          ></el-table-column>
          <el-table-column
            prop="estado"
            label="ESTADO"
            width="180"
          ></el-table-column>
        </el-table>
        <el-pagination
          :page-size="pagination.per_page"
          layout="prev, pager, next"
          :current-page="pagination.current_page"
          :total="pagination.total"
          @current-change="getActivesPaginate"
        ></el-pagination>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "DocumentoInventarioDetalle",
  data() {
    return {
      doc_inv_id: null,
      doc_inv: {},
      loading: false,
      user: this.$store.state.user,
      messages: {},
      data: [],
      pagination: {
        page: 1,
      },
    };
  },
  mounted() {
    this.doc_inv_id = this.$route.params.id;
    this.getDocInventory();
  },
  methods: {
    getDocInventory() {
      axios
        .get("/api/inventory2/doc_inv/" + this.doc_inv_id)
        .then((data) => {
          this.doc_inv = data.data;
          this.getActivesSearch();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getActivesSearch() {
      axios.get("/api/inventory2/search/" + this.doc_inv.no_cod, {
        params: {
          page: this.pagination.page,
          idOffice: this.doc_inv.oficina.id,
          idSubOffices: this.doc_inv.sub_ofc_cod,
        },
      }).then((data) => {
          this.loading = false;
          this.data = Object.values(data.data.data);

          this.pagination = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getActivesPaginate(page) {
      this.pagination.page = page;
      this.getActives("");
    },
    test() {
      alert("bienvenido al modulo");
    },
    formatResponsable(responsable) {
      return {
        cargo: responsable.descripcion,
        responsable:
          responsable.paterno.trim() +
          " " +
          responsable.materno.trim() +
          " " +
          responsable.nombres.trim(),
        nro_dip: responsable.nro_dip,
      };
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped>
.el-tag {
  margin: 2px;
}
</style>