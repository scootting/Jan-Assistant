<template>
  <div>
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
          placeholder="INSERTE EL ACTIVO A BUSCAR"
          v-model="writtenTextParameter"
          class="input-with-select"
          @keyup.enter.native="getActives"
        >
        </el-input>
        <el-select v-model="idOficce" placeholder="SELECCIONAR UNIDAD" filterable remote :remote-method="getUnidades" >
          <el-option v-for="item in unidades"
            :key="item.id"
            :label="item.descripcion"
            :value="item.id">
          </el-option>
        </el-select>
        <el-select v-model="idsSubOffices" multiple placeholder="SELECIONAR SUB UNIDAD" clearable filterable>
          <el-option v-for="item in subUnidades"
            :key="item.id"
            :label="item.descripcion"
            :value="item.id">
          </el-option>
        </el-select>
       <el-button
            icon="el-icon-search"
            @click="getActives"
       ></el-button>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="data" style="width: 100%">
          <el-table-column label="Identificador">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.id }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="oficina"
            label="OFICINA"
          ></el-table-column>
          <el-table-column
            prop="descripcion"
            label="SUB OFICINA"
          ></el-table-column>
          <el-table-column
            prop="des"
            label="DESCRIPCION"
          ></el-table-column> 
           <el-table-column
            prop="estado"
            label="ESTADO"
          ></el-table-column>
          <el-table-column align="right" width="220">
            <template slot-scope="scope">
              <el-button
                @click="EditActive(scope.$index, scope.row)"
                type="primary"
                plain
                size="mini"
                >Editar</el-button
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
  </div>
</template>

<script>
export default {
  name: "Reasignar_activos",
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
      idOficce:null,
      idsSubOffices:[],
      unidades: [],
      subUnidades:[],
    };
  },
  mounted() {
    this.getActives();
    this.getUnidades('');
  },
  watch: {
    idOficce(newVal,oldVal){
      if(newVal)
        this.onChangeUnidades();
      else {
        this.subUnidades=[]
      }
      this.idSubOffices=[];
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
            idOffice: this.idOficce,
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
      this.getActives('');
    },
    getUnidades(keyWord) {
      axios
        .get("/api/inventory2/unidad/", {
          params: { keyWord: keyWord.toUpperCase()},
        })
        .then((data) => {
          this.unidades = Object.values(data.data.data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onChangeUnidades(){
      this.getSubUnidades();
    },
    getSubUnidades() {
      axios
        .get("/api/inventory2/sub_unidad", {
          params: {idOffice: this.idOficce},
        })
        .then((data) => {
          this.subUnidades = data.data;
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

    test() {
      alert("bienvenido al modulo");
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped></style>
