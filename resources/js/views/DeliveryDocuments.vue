<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Documentos de Compra</span>
        <el-button
          style="text-align: right; float: right"
                    size="small"
                    type="primary"
                    icon="el-icon-plus"
          @click="newDeliveryDocument"
        >Nuevo documento de compra</el-button>
      </div>
      <div style="margin-top: 15px;">
        <el-input placeholder="Please input" v-model="search" class="input-with-select">
          <el-select v-model="selectParameter" slot="prepend" placeholder="Select">
            <el-option
              v-for="item in parameters"
              :label="item.attribute"
              :value="item.value"
              :key="item.value"
            ></el-option>
          </el-select>
        </el-input>
      </div>
      <br />
      <div>
        <el-search-table-pagination
        
          style="width: 100%"
          type="local"
          :data="list.filter(data => !search || data[selectParameter].toLowerCase().includes(search.toLowerCase()))"
          :page-sizes="[10, 15]"
          :columns="columns"
        >
          <!--Botones para redirigir a editar y a mostrar-->
          <!--Editar-->
          <template slot="nro_doc" slot-scope="scope">
            <el-button type="primary" plain @click="redirectVUE(scope.row)" >
              {{
              scope.row.edit
              }}Editar
            </el-button>
          </template>
          <!--Mostrar-->
          <template slot="fecha_doc">
            <el-button type="danger" plain @click="generatePDF" >Mostrar</el-button>
          </template>
        </el-search-table-pagination>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "DocumentoDeCompra",
  data() {
    return {
      user: this.$store.state.user,
      gestion: this.$store.state.user.gestion,
      selectParameter: "nro_doc",
      writtenTextParameter: "",
      parameters: [
        { value: "nro_doc", label: "Nro_doc" },
        { value: "fecha_doc", label: "Fecha" },
        { value: "responsable", label: "Resposable" },
        { value: "ofc_des", label: "Oficina" }
      ],
      messages: {},
      data: {},
      list: [],
      search: "",
      columns: [
        { prop: "nro_doc", label: "Nro_doc", width: 140 },
        { prop: "fecha_doc", label: "Fecha", width: 140 },
        { prop: "responsable", label: "Resposable", width: 180 },
        { prop: "ofc_des", label: "Oficina", width: 180 },
        {
          prop: "nro_doc",
          label: "Editar",
          width: 140,
          slotName: "nro_doc"
        },
        {
          prop: "nro_doc",
          label: "Mostrar",
          width: 140,
          slotName: "fecha_doc"
        }
      ]
    };
  },
  created() {
    var app = this;
    axios
      .get("/api/deliveryDocuments/" + app.user.gestion)
      .then(response => {
        console.log(response);
        app.list = response.data;
      })
      .catch(error => {
        this.error = error;
        this.$notify.error({
          title: "Error",
          message: this.error.message
        });
      });
  },
  mounted() {},
  methods: {
    test() {
      alert("bienvenido al modulo de compra");
    },
    redirectVUE(scope) {
      this.$store.state.encargado = scope;
      this.$router.push({ name: "editdeliverydocument" });
      console.log("envio de datos delivery"+scope);
    },
    generatePDF() {
      console.log("imprimir doc");
    },
    newDeliveryDocument(scope) {
      console.log(scope);
      this.$store.state.encargado = scope;
      this.$router.push({ name: "adddeliverydocument" });
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.el-card {
  background: #ffffff;
  
  /*background: #ffffff;
    background: #DEDFD9;
    background: #EBEEF4;
    background: #d3dce6;
    */
}
.el-form {
  padding-left: 120px;
  padding-right: 120px;
  padding-top: 60px;
}
</style>