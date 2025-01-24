<template>
  <div>
    <el-card>
      <div id="app" class="container">
        <div class="header">
          <h1>Venta de Valores en Línea</h1>
          <el-button type="primary">Ayuda</el-button>
        </div>

        <div class="messages">
          <el-alert title="¿Qué debo hacer? Seleccione los valores que desea adquirir usando la opción agregar..."
            type="error" show-icon></el-alert>
          <el-alert title="¿Cuáles son los métodos de pago? Puede pagar mediante..." type="success"
            show-icon></el-alert>
          <el-alert title="¿Qué precauciones tomar? Guarde siempre su código QR..." type="warning" show-icon></el-alert>
          <el-alert title="¿Cuánto tiempo tengo para pagar? El código tiene una validez de 7 días..." type="error"
            show-icon></el-alert>
        </div>

        <div class="section">
          <h2>Valores en Línea que Puede Adquirir</h2>
          <div class="desktop-view">
            <el-table :data="offered" border style="width: 100%">
              <el-table-column prop="descripcion" label="Descripción" width="50%"></el-table-column>
              <el-table-column prop="cantidad" label="Cantidad" width="15%"></el-table-column>
              <el-table-column prop="precio" label="Precio" width="15%"></el-table-column>
              <el-table-column label="Acción" width="20%">
                <template slot-scope="scope">
                  <el-button type="success" size="small" @click="agregarValor(scope.row)">Agregar</el-button>
                </template>
              </el-table-column>
            </el-table>
          </div>

          <div class="mobile-view">
            <div v-for="valor in offered" :key="valor.id" class="mobile-card">
              <div class="mobile-card-header">{{ valor.descripcion }}</div>
              <div class="mobile-card-content">
                <div><strong>Cantidad:</strong> {{ valor.cantidad }}</div>
                <div><strong>Precio:</strong> {{ valor.precio }}</div>
              </div>
              <div class="mobile-card-actions">
                <el-button type="success" size="small" @click="agregarValor(valor)">Agregar</el-button>
              </div>
            </div>
          </div>
        </div>

        <div class="section">
          <h2>Valores en Línea Solicitados para su Compra</h2>
          <div class="desktop-view">
            <el-table :data="offered" border style="width: 100%">
              <el-table-column prop="descripcion" label="Descripción" width="50%"></el-table-column>
              <el-table-column prop="cantidad" label="Cantidad" width="15%"></el-table-column>
              <el-table-column prop="precio" label="Precio" width="15%"></el-table-column>
              <el-table-column label="Acción" width="20%">
                <template slot-scope="scope">
                  <el-button type="danger" size="small" @click="quitarValor(scope.row)">Quitar</el-button>
                </template>
              </el-table-column>
            </el-table>
          </div>

          <div class="mobile-view">
            <div v-for="valor in offered" :key="valor.id" class="mobile-card">
              <div class="mobile-card-header">{{ valor.descripcion }}</div>
              <div class="mobile-card-content">
                <div><strong>Cantidad:</strong> {{ valor.cantidad }}</div>
                <div><strong>Precio:</strong> {{ valor.precio }}</div>
              </div>
              <div class="mobile-card-actions">
                <el-button type="danger" size="small" @click="quitarValor(valor)">Quitar</el-button>
              </div>
            </div>
          </div>
        </div>

        <el-button type="primary" size="large" @click="guardarSolicitud">Guardar la Solicitud de Valores en
          Línea</el-button>
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
      message: '',
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
      if (row.cod_val == '9365') {
        this.message = "Este valorado solo es para estudiantes de la CUIDAD DE POTOSI y que cuentan con una MATRICULA VIGENTE en la U.A.T.F., desea continuar?";
      }
      if (row.cod_val == '9366') {
        this.message = "Este valorado solo es para estudiantes de la CUIDAD DE POTOSI y que NO cuentan con una MATRICULA VIGENTE en la U.A.T.F(PERSONAS PARTICULARES), desea continuar?";
      }
      if (row.cod_val == '9367') {
        this.message = "Este valorado solo es para estudiantes de la CUIDAD DE TUPIZA y que cuentan con una MATRICULA VIGENTE en la U.A.T.F., desea continuar?";
      }
      if (row.cod_val == '9368') {
        this.message = "Este valorado solo es para estudiantes de la CUIDAD DE TUPIZA y que NO cuentan con una MATRICULA VIGENTE en la U.A.T.F(PERSONAS PARTICULARES), desea continuar?";
      }

      if (this.message == '') {
        this.acquired.push(row);
      }
      else {
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
.container {
  padding: 20px;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.messages {
  margin-bottom: 20px;
}

.messages .el-alert {
  margin-bottom: 10px;
}

.section {
  margin-bottom: 20px;
}

.mobile-card {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 10px;
  margin-bottom: 10px;
}

.mobile-card-header {
  font-weight: bold;
  margin-bottom: 10px;
}

.mobile-card-content {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
}

.mobile-card-actions {
  text-align: right;
}

/* Responsive Styles */
@media (min-width: 769px) {
  .mobile-view {
    display: none;
  }
}

@media (max-width: 768px) {
  .desktop-view {
    display: none;
  }
}
</style>
