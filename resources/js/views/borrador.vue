<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>certificado de no tener cuentas pendientes </span>
        <el-button
          style="float: right; padding: 3px 0"
          type="text"
          @click="test"
          >ayuda</el-button
        >
      </div>
      <div>
        <form>
          <el-row>
            <el-col>
              <el-select v-model="value" clearable placeholder="elegir tramite">
                <el-option
                  v-for="item in options"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                >
                </el-option>
              </el-select>

              <el-button type="primary" plain>Seleccionar</el-button>
            </el-col>
            <br /><br /><br />
            <el-col>
              <el-table :data="tableData" style="width: 100%">
                <el-table-column fixed prop="date" label="Codigo doc" width="300">
                    <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.date }}</el-tag>
              </div>
            </template>
                </el-table-column>
                <el-table-column prop="name" label="Nombre de documento" width="450">
                   
                </el-table-column>
                <el-table-column prop="zip" label="estado" width="120">
                </el-table-column>
                <el-table-column fixed="right" label="Operaciones" width="250">
                  <template>
                    <el-button @click="handleClick" type="text" size="small"
                      >Imprimir</el-button
                    >
                    <el-button type="text" size="small">Anular</el-button>
                  </template>
                </el-table-column>
              </el-table>
            </el-col>
          </el-row>
        </form>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "",
  data() {
    return {
      options: [
        {
          value: "Option1",
          label: "0014-Option1",
        },
        {
          value: "Option2",
          label: "0015-Option2",
        },
        {
          value: "Option3",
          label: "0016-Option3",
        },
        {
          value: "Option4",
          label: "0017-Option4",
        },
        {
          value: "Option5",
          label: "0018-Option5",
        },
      ],
      value: "",
      tableData: [
        {
          date: "0014-1368295",
          name: "tramite 1",
          zip: "Verificado",
        },
        {
          date: "0015-1368295",
          name: "tramite 2",
          zip: "observado",
        },
        {
          date: "0016-1368295",
          name: "tramite 3",
          zip: "observado",
        },
      ],
    };
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    handleClick() {
      console.log("click");
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.el-row {
  margin-bottom: 20px;
}
.el-col {
  border-radius: 4px;
}
.bg-purple-dark {
  background: #99a9bf;
}
.bg-purple {
  background: #d3dce6;
}
.bg-purple-light {
  background: #e5e9f2;
}
.grid-content {
  border-radius: 4px;
  padding: 15px;
  min-height: 36px;
}
.row-bg {
  padding: 10px 0;
  background-color: #f9fafc;
}
</style>
