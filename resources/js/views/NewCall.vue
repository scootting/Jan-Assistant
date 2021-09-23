<template>
  <div>
    <el-card>
      <div slot="header" class="clearfix">
        <span>Modulo de Nueva convocatoria </span>
        <el-button
          style="float: right; padding: 3px 0"
          type="text"
          @click="test"
          >ayuda</el-button
        >
      </div>
      <div>
        <el-form ref="form" :model="form" label-width="120px">
          <el-form-item label="Codigo">
            <el-input v-model="form.codigo" disabled></el-input>
          </el-form-item>
          <el-form-item label="Descripcion">
            <el-input type="textarea" v-model="form.descripcion"></el-input>
          </el-form-item>

          <el-form-item label="Documento PDF">
            <el-upload
              class="upload-demo"
              :on-preview="handlePreview"
              :on-remove="handleRemove"
              multiple
              :limit="3"
              :on-exceed="handleExceed"
              :file-list="fileList"
            >
              <el-button size="small" type="primary"
                >Clic para subir archivo</el-button
              >
            </el-upload>
          </el-form-item>
          <el-form-item label="Valido hasta:">
            <el-col :span="11">
              <el-date-picker
                type="date"
                placeholder="seleccionar fecha"
                v-model="form.date1"
                style="width: 100%"
              ></el-date-picker>
            </el-col>
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
  name: "nueva convocatoria",
  data() {
    return {
      form: {
        codigo: "00001",
        descripcion: "",
        date1: "",
        fileList: [
          {
            name: "food.jpeg",
            url: "https://fuss10.elemecdn.com/3/63/4e7f3a15429bfda99bce42a18cdd1jpeg.jpeg?imageMogr2/thumbnail/360x360/format/webp/quality/100",
          },
          {
            name: "food2.jpeg",
            url: "https://fuss10.elemecdn.com/3/63/4e7f3a15429bfda99bce42a18cdd1jpeg.jpeg?imageMogr2/thumbnail/360x360/format/webp/quality/100",
          },
        ],
        resource: "",
        desc: "",
      },
    };
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
  },
};
</script>

<style>
</style>