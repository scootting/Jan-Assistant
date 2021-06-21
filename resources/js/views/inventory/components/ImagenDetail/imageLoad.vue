<template>
  <div>
    <el-upload
      class="upload-demo"
      drag
      action="/api/inventory2/upload"
      accept=".png"
      :on-success="handleSuccessFile"
      :on-remove="handleRemove"
      :headers="requestHeaders"
      :data="{ datasource: JSON.stringify(info) }"
      :show-file-list="false"
      multiple
    >
      <i class="el-icon-upload"></i>
      <div class="el-upload__text">
        Suelta tu archivo aquí o
        <em>haz clic para cargar imagen</em>
      </div>
    </el-upload>
  </div>
</template>
<script>
export default {
  name: "ImageUpload",
  props: ["info"],
  data() {
    return {
      messages: {},
      fileList: [],
      file: "",
      files: "",
      path: null,
    };
  },
  created() {
    //this.getFiles();
  },
  computed: {
    csrfToken: function () {
      return window.axios.defaults.headers.common["X-CSRF-TOKEN"];
    },
    AuthorizationToken(){
      return 'Bearer '+this.$store.state.token;
    },
    requestHeaders(){
      return {
        'X-CSRF-TOKEN': this.csrfToken,
        Authorization: this.AuthorizationToken,
      };
    }
  },
  methods: {
    /* *** Cuando se eliminar el archivo satisfactoriamente *** */
    handleRemove(file, fileList) {
      axios.get("/api/inventory2/delete/upload-folder/" + file).then((res) => {
        this.getFiles();
      });
      console.log(file, fileList);
      this.fileList = FileList;
    },
    /* *** Cuando se agrega el archivo satisfactoriamente *** */
    handleSuccessFile(response, file, fileList) {
      this.$message({
        message: "Gracias, acaba de subir el archivo " + file.name + ".",
        type: "success",
      });
      this.$emit('on-success',response);
    },
    deleteFile(file) {
      axios.get("/api/inventory2/delete/" + file).then((res) => {
        this.getFiles();
      });
    },
    downloadFile(file) {
      axios
        .get("/api/inventory2/download/" + file, {
          responseType: "arraybuffer",
        })
        .then((res) => {
          let blob = new Blob([res.data], { type: "application/*" });
          let link = document.createElement("a");
          link.href = window.URL.createObjectURL(blob);
          link.download = file;
        });
    },
  },
};
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
