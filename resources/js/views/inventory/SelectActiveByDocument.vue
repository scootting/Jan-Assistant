<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>activos fijos</span>
        <el-button
          style="text-align: right; float: right"
          size="small"
          type="primary"
          icon="el-icon-plus"
          @click="initPrintSelectedFixedAssets"
          >imprimir</el-button
        >
      </div>
      <br />
      <div>
        {{ nro_doc }}
        <el-table
          v-loading="loading"
          :data="dataFixedAssets"
          style="width: 100%"
          height="450"
          @selection-change="handleSelectionChange"
        >
          <el-table-column type="selection"> </el-table-column>
          <el-table-column prop="codigo" label="codigo">
            <template slot-scope="scope">
              <el-tag size="success" type="info">{{ scope.row.codigo }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="act_des" label="descripcion"></el-table-column>
        </el-table>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "selectactivebydocument",
  data() {
    return {
      nro_doc: "",
      messages: {},
      dataFixedAssets: [],
      selectedFixedAssets: [],
      pagination: {
        page: 1,
      },
      loading: true,
    };
  },
  mounted() {
    let app = this;
    app.nro_doc = this.$route.params.id;
    axios
      .post("/api/selectActivebyDocument", {
        id: app.nro_doc,
      })
      .then((response) => {
        app.loading = false;
        app.dataFixedAssets = response.data;
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
    initPrintSelectedFixedAssets() {
      let list = [];
      for (var item in this.selectedFixedAssets) {
        list.push(this.selectedFixedAssets[item]["codigo"]);
      }
      console.log('aqui esta la lista',list);
      if (list.length != 0) {
        axios({
          url: "/api/reportSelectedActive/",
          params:{lista: list},
          method: "GET",
          responseType: "arraybuffer",
        }).then((response) => {
          console.log(response.data);
          console.log("1");
          let blob = new Blob([response.data], {
            type: "application/pdf",
          });
          let link = document.createElement("a");
          link.href = window.URL.createObjectURL(blob);
          console.log(blob);
          let url = window.URL.createObjectURL(blob);
          window.open(url);
        });
        //link.href = window.URL.createObjectURL(blob);
        //link.download = "test.pdf";
        //link.click();
      } else {
        alert("debe seleccionar por lo menos un activo por favor");
      }
    },
    handleSelectionChange(val) {
      this.selectedFixedAssets = val;
    },
  },
};
</script>
<style scoped>
.el-input .el-select {
  width: 180px;
}
</style>