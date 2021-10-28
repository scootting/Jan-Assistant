<template>
  <div>
    <el-card>
      <div slot="header" class="clearfix">
        <span>Solo valido para depositos bancarios</span>
        <el-button
          style="float: right; padding: 3px 0"
          type="text"
          @click="test"
          >ayuda</el-button
        >
      </div>
      <div>
        <el-form ref="form" :model="form" label-width="150px">
          <el-form-item label="Diplomado">
            <el-select
              v-model="form.descripcion"
              filterable
              remote
              placeholder="ingrese su busqueda"
              :remote-method="getValorMaterial"
            >
              <el-option
                v-for="item in valorMar"
                :key="item.cod_val"
                :label="item.des_dip"
                :value="item.cod_val"
              >
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Codigo">
            <el-input v-model="form.descripcion" disabled></el-input>
          </el-form-item>
          <el-form-item label="numero de comprobante">
            <el-input v-model="form.NroComp"></el-input>
          </el-form-item>
          <el-form-item label="monto">
            <el-input v-model="form.monto"></el-input>
          </el-form-item>
          <el-form-item label="Fecha de la Transaccion:">
            <el-col :span="11">
              <el-date-picker
                type="date"
                placeholder="seleccionar fecha"
                v-model="form.date1"
                style="width: 100%"
              ></el-date-picker>
            </el-col>
          </el-form-item>
          <el-form-item label="Documento PDF">
            <!-- <el-upload
              class="upload-demo"
              :on-preview="handlePreview"
              :on-remove="handleRemove"
              multiple
              :limit="3"
              :on-exceed="handleExceed"
              :file-list="fileList"
            >
              <el-button size="small" type="primary"
                >Clic para subir comprobante</el-button
              >
            </el-upload> -->
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onSubmit">Guardar</el-button>
            <el-button>Cancelar</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "NuevaConvocatoria",
  data() {
    return {
      valorMar:[],
      loading: false,
      form: {
        codigo: "00001",
        descripcion: "",
        date1: "",
        fileList: [],
        resource: "",
        desc: "",
      },
    };
  },
  mounted(){
    this.getValorMaterial("");
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    onSubmit() {
      console.log("submit!");
    },
    handleRemove(file, fileList) {
      console.log(file, fileList);
    },
    handlePreview(file) {
      console.log(file);
    },
    handleExceed(files, fileList) {
      this.$message.warning(
        `El límite es 3, haz seleccionado ${
          files.length
        } archivos esta vez, añade hasta ${files.length + fileList.length}`
      );
    },
    getValorMaterial(keyWord){
         axios
        .get("/api/curso/", {
          params: { keyWord: keyWord.toUpperCase() },
        })
        .then((data) => {
          this.valorMar = Object.values(data.data.data);;
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
};
</script>
<style>
</style>