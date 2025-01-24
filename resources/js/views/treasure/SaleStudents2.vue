<template>
  <div>
    <el-card class="box-card">
      <div class="container">
        <div class="header">
            <h1>Venta de Valores en Línea</h1>
            <button class="btn">Ayuda</button>
        </div>

        <div class="messages">
            <div class="alert">¿Qué debo hacer? Seleccione los valores que desea adquirir usando la opción agregar...</div>
            <div class="info">¿Cuáles son los métodos de pago? Puede pagar mediante...</div>
            <div class="warning">¿Qué precauciones tomar? Guarde siempre su código QR...</div>
            <div class="alert">¿Cuánto tiempo tengo para pagar? El código tiene una validez de 7 días...</div>
        </div>

        <div class="section">
            <h2>Valores en Línea que Puede Adquirir</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Derecho a Certificación...</td>
                        <td>1.00</td>
                        <td>30.00</td>
                        <td><button class="btn green">Agregar</button></td>
                    </tr>
                    <tr>
                        <td>Aporte Mensual...</td>
                        <td>1.00</td>
                        <td>300.00</td>
                        <td><button class="btn green">Agregar</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>Valores en Línea Solicitados para su Compra</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Derecho a Certificación...</td>
                        <td>1.00</td>
                        <td>30.00</td>
                        <td><button class="btn red">Quitar</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button class="btn" onclick="confirmarPago()">Guardar la Solicitud de Valores en Línea</button>
    </div>
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
      console.log(this.client);
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
