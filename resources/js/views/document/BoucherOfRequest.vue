<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>registro del comprobante de pago</span>
      </div>
      <el-row :gutter="20">
        <p>
          <el-alert title="importante" type="error"
            description="las solicitudes tienen validez de 30 dias calendario, durante ese periodo debe realizar la cancelacion de importe, a traves de la cuenta unica de la universidad"
            show-icon>
          </el-alert>
        </p>
        <el-col :span="12">
          <div class="grid-content bg-purple">
            <p>datos de la solicitud</p>

            <el-form ref="form" :model="this.onlyRequest" label-width="200px" size="mini">
              <el-form-item label="numero de solicitud">
                <el-input v-model="onlyRequest.idc" disabled></el-input>
              </el-form-item>
              <el-form-item label="fecha en la que se realizo">
                <el-input v-model="onlyRequest.fecha" disabled></el-input>
              </el-form-item>
              <el-form-item label="descripcion">
                <el-input v-model="onlyRequest.des_per" disabled></el-input>
              </el-form-item>
              <el-form-item label="importe a cancelar">
                <el-input v-model="onlyRequest.importe" disabled></el-input>
              </el-form-item>
              <el-form-item label="estado">
                <el-input v-model="onlyRequest.estado" disabled></el-input>
              </el-form-item>
            </el-form>
          </div>
          <p></p>
          <div class="grid-content bg-purple">
            <p>depositos registrados</p>
            <el-table v-loading="loading" :data="boucherRequest" style="width: 100%">
              <el-table-column prop="fecha" label="fecha"></el-table-column>
              <el-table-column prop="boucher" label="numero"></el-table-column>
              <el-table-column prop="imp_bou" label="importe"></el-table-column>
            </el-table>
          </div>
        </el-col>
        <el-col :span="12">
          <div class="grid-content bg-purple">
            <p>registrar deposito</p>
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
                  <p></p>
                  <el-button slot="trigger" size="small" type="primary">subir respaldo de comprobante de pago
                  </el-button>
                  <div slot="tip" class="el-upload__tip">
                    Solo archivos jpg/png/pdf con un tamaño menor de 500kb
                  </div>
                </el-upload>
                <el-button type="success" size="small" @click="storeBoucherOfRequest()">guardar registro de comprobante
                  de pago
                </el-button>
              </el-form-item>
            </el-form>
          </div>
          <br>
          <el-button type="danger" size="small" style="float: right;" @click="storeStatusOfRequest()">terminar registro de
            comprobante de pago
          </el-button>
        </el-col>
      </el-row>
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
      loading: true,
      dataRequest: [],
      boucherRequest: [],
      onlyRequest: {},
      requestHeaders: {
        "X-CSRF-TOKEN": window.axios.defaults.headers.common["X-CSRF-TOKEN"],
        Authorization: "Bearer " + this.$store.state.token,
      },
      request: {
        tag: this.$route.params.id,
        boucher: "",
        fecha: "",
        monto: 0.0,
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
        console.log(response);
        app.dataRequest = response.data.data;
        app.boucherRequest = response.data.boucher;
        app.onlyRequest = app.dataRequest[0];
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
      this.$alert('Gracias, acaba de subir el comprobante de pago ' + file.name + ' satifactoriamente.', 'confirmacion', {
        confirmButtonText: 'bueno',
      });
      this.request = [];
      this.document = [];
      this.getDataRequestById();
      console.log(response, file, fileList);
      this.fileList = fileList;
    },

    async storeStatusOfRequest(){

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

.el-input__inner{
  color:#000 !important;
}
</style>
