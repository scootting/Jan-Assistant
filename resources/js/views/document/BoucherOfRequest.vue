<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>[]</span>
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
            <p>datos de la solicitud</p>
            <!--
            -->

            <el-form ref="form" :model="this.onlyRequest" label-width="200px" size="mini">
              <el-form-item label="tag">
                <el-input v-model="onlyRequest.idc" disabled></el-input>
              </el-form-item>
              <el-form-item label="fecha">
                <el-input v-model="onlyRequest.fecha" disabled></el-input>
              </el-form-item>
              <el-form-item label="detalle">
                <el-input v-model="onlyRequest.des_per" disabled></el-input>
              </el-form-item>
              <el-form-item label="importe">
                <el-input v-model="onlyRequest.importe" disabled></el-input>
              </el-form-item>
              <el-form-item label="estado">
                <el-input v-model="onlyRequest.estado" disabled></el-input>
              </el-form-item>
            </el-form>
          </div>
        </el-col>
        <el-col :span="12">
          <div class="grid-content bg-purple">
            <p>deposito</p>
            <el-form ref="form" :model="this.request" label-width="200px" size="mini">
              <el-form-item label="numero de deposito ">
                <el-input v-model="request.boucher"></el-input>
              </el-form-item>
              <el-form-item size="small" label="fecha del deposito" prop="fecha">
                <el-date-picker size="small" type="date" v-model="request.fecha" style="width: 100%" format="dd-MM-yyyy"
                  value-format="yyyy-MM-dd">
                </el-date-picker>
              </el-form-item>
              <el-form-item label="monto">
                <el-input-number size="medium" v-model="request.monto" :precision="2" :step="0.5"></el-input-number>
              </el-form-item>
              <el-form-item>
                <el-upload ref="upload" action="/api/storeBoucherOfRequest" :auto-upload="false" :file-list="document"
                  :multiple="false" :limit="1" :data="request" accept=".pdf, .png, .jpeg" :headers="requestHeaders"
                  :on-success="handleSuccessBoucher" :on-remove="test">
                  <!---->
                  <el-button slot="trigger" size="small" type="primary">Selecciona un archivo</el-button>
                  <div slot="tip" class="el-upload__tip">Solo archivos jpg/png/pdf con un tamaño menor de 500kb</div>
                </el-upload>
              </el-form-item>
            </el-form>
          </div>
        </el-col>
      </el-row>
      <el-button type="primary" size="small" @click="storeBoucherOfRequest()">Agregar Deposito</el-button>
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
      id: this.$route.params.id,
      dataRequest: [],
      onlyRequest:[],
      requestHeaders: {
        'X-CSRF-TOKEN': window.axios.defaults.headers.common["X-CSRF-TOKEN"],
        Authorization: 'Bearer ' + this.$store.state.token,
      },
      request: {
        tag: this.$route.params.id,
        boucher: "",
        fecha: "",
        monto: 0.00,
      },
      document: [],
    };
  },
  mounted() {
    this.getDataRequestById();
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },

    //  *  D3. Obtener la informacion por cada solicitud
    //  * {id: id de la solicitud }
    async getDataRequestById() {
      var app = this;
      try {
        let response = await axios.post("/api/getDataRequestById/", {
          id: app.id,
        });
        app.loading = false;
        app.dataRequest = response.data;
        app.onlyRequest = app.dataRequest[0];
        console.log(app.onlyRequest);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    //  *  D2. Guardar los boucher generados por cada solicitud
    //  * {boucher: imagen del boucher }
    //  * {request: informacion del boucher }
    async storeBoucherOfRequest() {
      var app = this;
      this.$refs.upload.submit();
    },
    handleSuccessBoucher(response, file, fileList) {
      this.$message({
        message: 'Gracias, acaba de subir el archivo ' + file.name + '.',
        type: 'success'
      });
      console.log(response, file, fileList);
      this.fileList = fileList;
    }
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
  