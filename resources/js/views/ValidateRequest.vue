<template>
  <div>
    <el-card>
      <div slot="header" class="clearfix">
        <span>Modulo de validacion de requerimientos </span>
        <el-button
          style="float: right; padding: 3px 0"
          type="text"
          @click="test"
          >ayuda</el-button
        >
      </div>
      <div>
        <el-form>
          <el-row>
            <el-col>
              <el-input
                placeholder="INSERTE UNA DESCRIPCION"
                class="input-with-select"
              >
                <el-button slot="append" icon="el-icon-search"></el-button>
              </el-input>
            </el-col>
            <el-col>
              <el-table :data="tableData" style="width: 100%">
                <el-table-column
                  fixed
                  prop="date"
                  label="Codigo doc"
                  width="300"
                >
                  <template slot-scope="scope">
                    <div slot="reference" class="name-wrapper">
                      <el-tag size="medium">{{ scope.row.date }}</el-tag>
                    </div>
                  </template>
                </el-table-column>
                <el-table-column
                  prop="name"
                  label="Nombre de documento"
                  width="450"
                >
                </el-table-column>
                <el-table-column
                  prop="zip"
                  label="estado"
                  width="250"
                >
                </el-table-column>
                <el-table-column prop="zip" label="Operacion" width="120">
                  <el-select v-model="value" placeholder="operacion">
                    <el-option
                      v-for="item in options"
                      :key="item.value"
                      :label="item.label"
                      :value="item.value"
                    >
                    </el-option>
                  </el-select>
                </el-table-column>
              </el-table>
            </el-col></el-row
          >
        </el-form>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "Validacion de solicitud",
  data() {
    return {
      options: [
        {
          value: "Aprobado",
          label: "Aprobado",
        },
        {
          value: "Observado",
          label: "Observado",
        },
      ],
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