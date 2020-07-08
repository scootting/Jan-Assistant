<template>
  <div>
    <el-card class="box-card">
      <el-table :data="tableData" style="width: 100%">
        <el-table-column label="codigo" width="180">
          <template slot-scope="scope">
            <el-popover trigger="hover" placement="top">
              <p>codigo: {{ scope.row.codigo }}</p>
              <p>titulo: {{ scope.row.titulo }}</p>
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.codigo }}</el-tag>
              </div>
            </el-popover>
          </template>
        </el-table-column>
        <el-table-column label="descripcion" width="500">
          <template slot-scope="scope">
            <i class="el-icon-time"></i>
            <span style="margin-left: 10px">{{ scope.row.descripcion }}</span>
          </template>
        </el-table-column>
        <el-table-column label="realizar">
          <template slot-scope="scope">
            <el-button size="mini" @click="initDoFormalities(scope.$index, scope.row)">Realizar</el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "Bienvenido",
  data() {
    return {
      messages: {},
      data: {},
      tableData: [
        {
          codigo: "00001",
          titulo: "Titulo en provision nacional",
          descripcion: "Descripcion de el titulo en provision nacional"
        },
        {
          codigo: "00002",
          titulo: "Titulo de bachiller",
          descripcion: "Decripcion del titulo de bachiller"
        },
        {
          codigo: "00003",
          titulo: "Titulo en provision nacional segunda version",
          descripcion: "Descripcion de el titulo en provision nacional segunda version"
        },
        {
          codigo: "00004",
          titulo: "Titulo de bachiller segunda version",
          descripcion: "Descripcion de el titulo de bachiller segunda version"
        }
      ]
    };
  },
  mounted() {},
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    initDoFormalities(){

    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
