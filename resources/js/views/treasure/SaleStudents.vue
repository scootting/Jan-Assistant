<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>venta de valores en linea</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
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
            <p>valores en linea que puede adquirir</p>
            <el-table v-loading="loading" :data="offered" style="width: 100%">
              <el-table-column prop="des_val" label="descripcion" width="350"></el-table-column>
              <el-table-column prop="pre_uni" label="precio" width="100" align="right"></el-table-column>
              <el-table-column align="right" width="100">
                <template slot-scope="scope">
                  <el-button @click="initAddValues(scope.$index, scope.row)" type="primary" size="mini" plain>Agregar
                  </el-button>
                </template>
              </el-table-column>
            </el-table>
          </div>
        </el-col>
        <el-col :span="12">
          <div class="grid-content bg-purple">
            <p>valores en linea solicitados para su compra</p>
            <el-table :data="acquired" style="width: 100%" show-summary sum-text="importe total a cancelar">
              <el-table-column prop="des_val" label="descripcion" width="350"></el-table-column>
              <el-table-column prop="pre_uni" label="precio" width="100" align="right"></el-table-column>
              <el-table-column align="right" width="100">
                <template slot-scope="scope">
                  <el-button @click="initRemoveValues(scope.$index, scope.row)" type="primary" size="mini" plain>Quitar
                  </el-button>
                </template>
              </el-table-column>
            </el-table>
          </div>
          <br>
          <div style="text-align: right; float: right">
            <el-tag type="success" effect="dark">EL IMPORTE TOTAL QUE DEBE CANCELAR ES: {{ total }}</el-tag>
            <el-button type="primary" size="small" @click="setValuesAcquired()">guardar la solicitud de valores en linea
            </el-button>
          </div>
        </el-col>
      </el-row>
      <el-row> </el-row>
    </el-card>
  </div>
</template>

<script>
import { allowedNodeEnvironmentFlags } from 'process';

export default {
  name: "",
  data() {
    return {
      client: this.$store.state.user,
      loading: true,
      offered: [],
      acquired: [],
      total: 0.00,
    };
  },
  mounted() {
    this.getValuesOffered();
  },
  methods: {
    test() {
      alert("llamar al 74246032 para asistencia tecnica");
    },
    //  *  T1. Obtener los valores para la venta en linea
    async getValuesOffered() {
      var app = this;
      try {
        let response = await axios.post("/api/getValuesOffered/", {
          year: app.client.gestion,
        });
        app.loading = false;
        app.offered = response.data;
        console.log(response.data);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    //  *  T2. Guardar los valores para la venta en linea
    async setValuesAcquired() {
      var app = this;
      console.log(app.acquired);
      try {
        if (app.acquired.length <= 0) {
          this.$alert('DEBE SELECCIONAR POR LO MENOS UN VALOR PARA CREAR LA SOLICITUD', 'HA OCURRIDO UN ERROR', {
            confirmButtonText: 'BUENO',
          });
        } else {
          let response = await axios.post("/api/setValuesAcquired/", {
            client: app.client,
            total: app.total,
            acquired: app.acquired,
            marker: "SALE",
          });
          this.$alert('ACABA DE CREAR UNA NUEVA SOLICITUD, SI CUENTA CON EL COMPROBANTE DE PAGO PUEDE REALIZAR SU REGISTRO', 'LO HA CONSEGUIDO', {
            confirmButtonText: 'BUENO',
          });
          console.log(response.data[0].ff_nueva_solicitud);
          let id = response.data[0].ff_nueva_solicitud;
          this.$router.push({
            name: "boucherofrequest",
            params: {
              id: id,
            },
          });
        }
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },
    // * FUNLOCAL. agregar valores que se van a comprar
    initAddValues(index, row) {
      this.total += parseFloat(row.pre_uni);
      this.acquired.push(row);
    },
    // * FUNLOCAL. Quitar valores que se iban a comprar
    initRemoveValues(index, row) {
      this.acquired.splice(index, 1);
      this.total -= parseFloat(row.pre_uni);
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
