<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Activos Fijos</span>
        <el-button
          style="text-align: right; float: right"
          size="small"
          type="primary"
          icon="el-icon-plus"
          @click="createActive"
          >Añadir nuevo activo</el-button
        >
      </div>
      <div style="margin-top: 15px">
        <el-input
          placeholder="INSERTE DESCRIPCION"
          v-model="writtenTextParameter"
          class="input-with-select"
          @keyup.enter.native="getActives"
          style="width: 200px"
        >
        </el-input>
        <el-select
          v-model="idOffice"
          placeholder="SELECCIONAR UNIDAD"
          filterable
          remote
          :remote-method="getUnidades"
        >
          <el-option
            v-for="item in unidades"
            :key="item.id"
            :label="item.descripcion"
            :value="item.id"
          >
          </el-option>
        </el-select>
        <select-sub-unidad :ofc-cod="this.codSoa" v-model="idsSubOffices" multiple />
        <!-- <el-select
          v-model="idsSubOffices"
          multiple
          placeholder="SELECIONAR SUB UNIDAD"
          clearable
          filterable
        >
          <el-option
            v-for="item in subUnidades"
            :key="item.id"
            :label="item.descripcion"
            :value="item.id"
          >
          </el-option>
        </el-select> -->
        <el-button icon="el-icon-search" @click="getActives"></el-button>
      </div>
      <br />
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
            prop="oficina"
            label="OFICINA"
            width="180"
          ></el-table-column>
          <el-table-column
            prop="descripcion"
            label="SUB OFICINA"
            width="150"
          ></el-table-column>
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
          <el-table-column align="right-center" width="220" label="Operaciones">
            <template slot-scope="scope">
              <el-button
                @click="EditActive(scope.$index, scope.row)"
                type="primary"
                plain
                size="mini"
                >Editar</el-button
              >
              <el-button
                @click="selectActiveQr(scope.row)"
                type="primary"
                plain
                size="mini"
                >Obtener QR</el-button
              >
            </template>
          </el-table-column>
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
    <el-dialog
      title="QR"
      :visible.sync="showQR"
      width="30%"
      @close="showQR = false"
    >
      <el-row type="flex" justify="center">
        <vue-qr :text="JSON.stringify(activoSelectQR)" :size="400"></vue-qr>
      </el-row>
      <span slot="footer">
        <el-button @click="showQR = false">Cancel</el-button>
        <el-button
          @click="
            $router.push({
              name: 'qrprint',
              params: { id: activoSelectQR.id, activo: activoSelectQR },
            })
          "
          >Imprimir</el-button
        >
      </span>
    </el-dialog>
  </div>
</template>

<script>
//QR,para usar con los activos fijos
import VueQr from "vue-qr";
import SelectSubUnidad from './components/selectSubUnidad.vue';
export default {
  name: "Reasignar_activos",
  components: { 
    VueQr,
    SelectSubUnidad
  },
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
      idOffice: null,
      idsSubOffices: [],
      unidades: [],
      subUnidades: [],
      activoSelectQR: null,
      showQR: false,
    };
  },
  mounted() {
    this.getActives();
    this.getUnidades("");
  },
  computed: {
    codSoa(){
      if(!this.idOffice)
        return null;
      return this.unidades.find(u => u.id === this.idOffice ).cod_soa;
    },
  },
  methods: {
    getActives() {
      this.loading = true;
      axios
        .get("/api/reasignacion/", {
          params: {
            page: this.pagination.page,
            descripcion: this.writtenTextParameter.toUpperCase(),
            codSoa: this.codSoa,
            idSubOffice: this.idsSubOffices,
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
    getActivesPaginate(page) {
      this.pagination.page = page;
      this.getActives("");
    },
    getUnidades(keyWord) {
      axios
        .get("/api/inventory2/unidad/", {
          params: { keyWord: keyWord.toUpperCase() },
        })
        .then((data) => {
          this.unidades = Object.values(data.data.data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    EditActive(index, row) {
      this.$router.push({
        name: "editactive",
        params: {
          id: row.id,
        },
      });
    },
    selectActiveQr(actv) {
      this.activoSelectQR = actv;
      this.showQR = true;
    },
    createActive() {
      this.$router.push({
        name: "newactive",
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
