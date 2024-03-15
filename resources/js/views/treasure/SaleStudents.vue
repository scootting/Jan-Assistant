<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>venta de valores en linea</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
      </div>
      <el-row :gutter="20">
          <el-alert title="Que debo hacer?" type="error"
            description="Seleccione los valores que desea adquirir usando la opcion agregar, para retirar solo presione el boton quitar, siempre este atento a la cantidad de valores que solicita ya que estos estaran a su nombre y no de terceros.">
          </el-alert>
          <br>
          <el-alert title="Cuales son los metodos de pago?" type="success"
            description="Codigo Qr, si cuenta con banca movil; o Codigo CPT, que le permite pagar en cualquier agencia del banco union, o a traves de uninet movil o uninet plus a traves de la opcion pago del estado.">
          </el-alert>
          <br>
          <el-alert title="Que precauciones tomar?" type="warning"
            description="Guarde siempre su codigo Qr o codigo CPT ante cualquier eventualidad y no comparta esta informacion ya que cada codigo es unico para cada persona.">
          </el-alert>
          <br>
          <el-alert title="cuanto tiempo tengo para pagar?" type="error"
            description="el codigo qr o codigo cpt tienen una validez de 7 dias calendario, durante ese periodo debe realizar la cancelacion del importe.">
          </el-alert>
          <br>
        <el-col :span="12">
          <div class="grid-content bg-purple">
            <p>valores en linea que puede adquirir</p>
            <el-table v-loading="loading" :data="offered" style="width: 100%">
              <el-table-column prop="des_val" label="descripcion" width="300"></el-table-column>
              <el-table-column prop="cantidad" label="cantidad" width="90" align="right"></el-table-column>
              <el-table-column prop="pre_uni" label="precio" width="90" align="right"></el-table-column>
              <el-table-column align="right" width="100" fixed="right">
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
              <el-table-column prop="des_val" label="descripcion" width="300"></el-table-column>
              <el-table-column prop="cantidad" label="cantidad" width="90" align="right"></el-table-column>
              <el-table-column prop="pre_uni" label="precio" width="90" align="right"></el-table-column>
              <el-table-column align="right" width="100" fixed="right">
                <template slot-scope="scope" v-if="scope.row.compuesto === 'U'">
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
      total: 1.00,
      dataRequest: {},
      message:'',
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
        let response = await axios.post("/api/getValuesOffered", {
          year: app.client.gestion,
        });
        app.loading = false;
        app.offered = response.data.valuesOffered;
        app.acquired = response.data.valuesAcquired;
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
        if (app.acquired.length <= 1) {
          this.$alert('DEBE SELECCIONAR POR LO MENOS UN VALOR PARA CREAR LA SOLICITUD', 'HA OCURRIDO UN ERROR', {
            confirmButtonText: 'BUENO',
          });
        } else {
          let response = await axios.post("/api/setValuesAcquired", {
            client: app.client,
            total: app.total,
            acquired: app.acquired,
            marker: "SALE",
          });
          /*
          this.$alert('acaba de crear una solicitud de venta de valores en linea, desea continuar?', 'LO HA CONSEGUIDO', {
            confirmButtonText: 'SI',
          });*/
          /*
          this.$router.push({
            name: "boucherofrequest",
            params: {
              id: id,
            },
          });
          */
          console.log(response);
          this.dataRequest = response.data.datos;
          console.log(this.dataRequest.urlRedireccion);
          window.location.href = this.dataRequest.urlRedireccion;
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
      console.log(row);
      if(row.cod_val == '9365'){
        this.message = "Este valorado solo es para estudiantes de la CUIDAD DE POTOSI y que cuentan con una MATRICULA VIGENTE en la U.A.T.F., desea continuar?";
      }
      if(row.cod_val == '9366'){
        this.message = "Este valorado solo es para estudiantes de la CUIDAD DE POTOSI y que NO cuentan con una MATRICULA VIGENTE en la U.A.T.F(PERSONAS PARTICULARES), desea continuar?";
      }
      if(row.cod_val == '9367'){
        this.message = "Este valorado solo es para estudiantes de la CUIDAD DE TUPIZA y que cuentan con una MATRICULA VIGENTE en la U.A.T.F., desea continuar?";
      }
      if(row.cod_val == '9368'){
        this.message = "Este valorado solo es para estudiantes de la CUIDAD DE TUPIZA y que NO cuentan con una MATRICULA VIGENTE en la U.A.T.F(PERSONAS PARTICULARES), desea continuar?";
      }

      if(this.message == ''){
        this.acquired.push(row);
      }
      else{
        this.$confirm(this.message, 'Alerta', {
          confirmButtonText: 'Agregar',
          cancelButtonText: 'Cancelar',
          type: 'warning'
        }).then(() => {
          this.acquired.push(row);
        }).catch(() => {
        });
        this.message = '';
      }
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
