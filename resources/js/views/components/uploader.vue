<template>
      <el-upload
        class="upload-demo"
        drag
        action="upload"
        accept=".dec"
        :on-success="handleSuccessFile"
        :on-remove="handleRemove"
        :headers="{ 'X-CSRF-TOKEN': csrf }"
        :data="{ 'datasource': JSON.stringify(info) }"
        multiple
      >
        <i class="el-icon-upload"></i>
        <div class="el-upload__text">
          Suelta tu archivo aquí o
          <em>haz clic para cargar</em>
        </div>
        <div slot="tip" class="el-upload__tip">Valido solo para archivos .dec del programa Facilito</div>
      </el-upload>
</template>

<script>
export default {
  props: ['csrf', 'info'],
  data() {
    return {
      messages: {},
      fileList: [],
      file: "",
      files: ""
    };
  },
  created() {
    //this.getFiles();
  },
  mounted() {},
  methods: {
    savePerson() {
      //alert("hola");
      event.preventDefault();
      var app = this;
      var newPerson = app.person;
      axios
        .post("upload", {
        })
        .then(function(response) {
          alert("se ha creado el registro de la persona");
        })
        .catch(function(response) {
          console.log(response);
          alert("no se puede crear el registro de la persona");
        });
    },
    test() {
      alert("bienvenido al modulo");
    },
    /* *** Cuando se eliminar el archivo satisfactoriamente *** */
    handleRemove(file, fileList) {
      console.log(file, fileList);
      this.fileList = FileList;
    },

    /* *** Cuando se agrega el archivo satisfactoriamente *** */
    handleSuccessFile(response, file, fileList) {
      console.log(response,file, fileList);
      this.fileList = fileList;
    },

    onFileChange(e) {
      this.file = e.target.files[0];
    },
    uploadFile() {
      let formData = new FormData();
      formData.append("file", this.file);
      axios
        .post("/api/upload", formData)
        .then(res => {
          this.getFiles();
        })
        .catch(err => {
          console.log("Error: ", err);
        });
    },
    deleteFile(file) {
      axios.get("/api/delete/" + file).then(res => {
        this.getFiles();
      });
    },
    getFiles() {
      axios.get("/api/files").then(res => {
        this.files = res.data;
      });
    },
    downloadFile(file) {
      axios
        .get("/api/download/" + file, { responseType: "arraybuffer" })
        .then(res => {
          let blob = new Blob([res.data], { type: "application/*" });
          let link = document.createElement("a");
          link.href = window.URL.createObjectURL(blob);
          link.download = file;
          link.click();
        });
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
