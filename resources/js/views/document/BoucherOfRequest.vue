<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Solicitud: </span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
      </div>
      <el-row :gutter="20">
        <p>
          <el-alert
            title="El cambio de la direccion, telefono, correo implica que sus nuevas solicitudes tendrán esta nueva informacion."
            type="success">
          </el-alert>
        </p>
        <el-col :span="12">
          <div class="grid-content bg-purple">
            <p>datos personales</p>
            <el-form ref="form" :model="this.request" label-width="200px" size="mini">
              <el-form-item label="numero de deposito ">
                <el-input v-model="request.boucher"></el-input>
              </el-form-item>
              <el-form-item size="small" label="fecha del deposito" prop="fecha">
                <el-date-picker size="small" type="date" v-model="request.fecha" style="width: 100%">
                </el-date-picker>
              </el-form-item>
              <el-form-item label="monto">
                <el-input-number size="medium" v-model="request.monto" :precision="2" :step="0.1"></el-input-number>
              </el-form-item>
              <el-form-item>
                <el-upload class="upload-demo" ref="upload"  action="/api/storeBoucherOfRequest" :auto-upload="false" :file-list="request.img"><!--:multiple="false" :limit="1"-->
                  <el-button slot="trigger" size="small" type="primary">Selecciona un archivo</el-button>
                  <div slot="tip" class="el-upload__tip">Solo archivos jpg/png/pdf con un tamaño menor de 500kb</div>
                </el-upload>
              </el-form-item>
            </el-form>
          </div>
        </el-col>
      </el-row>
      <el-button type="primary" size="small" @click="storeBoucherOfRequest()">enviar deposito</el-button>
      <el-row> </el-row>
    </el-card>
  </div>
</template>
  
<script>
export default {
  name: "",
  data() {
    return {
      client: this.$store.state.user,
      request: {
        boucher: "",
        fecha: "",
        img: [],
        monto: 0.00,
      },
    };
  },
  mounted() {
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    async storeBoucherOfRequest() {
      var app = this;
      this.$refs.upload.submit();
      console.log(app.request);
      console.log(app.request.img[0]);
      /*
      try {
        let response = await axios.post("/api/storeBoucherOfRequest", {
          persona: app.client,
          marker: "editar",
        });
        app.$alert("se ha actualizado la informacion correctamente!!! ...", "Gestor de mensajes", {
          dangerouslyUseHTMLString: true,
        });
        app.$store.commit("updateUser", app.client);
        this.$router.push({
          name: "welcome",
        });
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }*/
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
  