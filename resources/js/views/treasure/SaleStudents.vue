<template>
  <div>
    <el-card style="margin-bottom: 50px;">
      <div class="header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="card-title" style="margin: 10; font-weight: bold;">
          Venta de valores en linea
        </h3>
        <el-button type="primary" size="small" @click="test">
          ayuda
        </el-button>
      </div>
      <!-- Mensajes -->
      <div class="alerts-container">
        <el-alert title="Que debo hacer?" type="error" show-icon class="alert-space"
          description="Seleccione lo que desea adquirir usando la opcion agregar, para retirar solo presione el boton quitar, siempre este atento a la cantidad que solicita ya que estos estaran a su nombre y no de terceros.">
        </el-alert>
        <br>
        <el-alert title="Cuales son los metodos de pago?" type="success" show-icon class="alert-space"
          description="Codigo Qr, si cuenta con banca movil; o Codigo CPT, que le permite pagar en cualquier agencia del banco union, o a traves de uninet movil o uninet plus a traves de la opcion pago del estado.">
        </el-alert>
        <br>
        <el-alert title="Que precauciones tomar?" type="warning" show-icon class="alert-space"
          description="Guarde siempre su codigo Qr o codigo CPT ante cualquier eventualidad y no comparta esta informacion ya que cada codigo es unico para cada persona.">
        </el-alert>
        <br>
        <el-alert title="cuanto tiempo tengo para pagar?" type="error" show-icon class="alert-space"
          description="el codigo qr o codigo cpt tienen una validez de 7 dias calendario, durante ese periodo debe realizar la cancelacion del importe.">
        </el-alert>
      </div>

      <!-- Tabla para dispositivos grandes -->
      <h2 v-if="!isSmallDevice">
        <p>valores universitarios disponibles</p>
      </h2>
      <el-table v-if="!isSmallDevice" :data="offered" border style="width: 100%; margin-top: 15px">
        <el-table-column prop="des_val" label="descripcion" width="950"></el-table-column>
        <el-table-column prop="cantidad" label="cantidad" width="90" align="right"></el-table-column>
        <el-table-column prop="pre_uni" label="precio" width="90" align="right"></el-table-column>
        <el-table-column align="right" width="150" fixed="right">
          <template slot-scope="scope">
            <el-button @click="initAddValues(scope.$index, scope.row)" type="primary" size="mini">Agregar
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <h2 v-if="!isSmallDevice">
        <p>valores universitarios seleccionados</p>
      </h2>
      <el-table v-if="!isSmallDevice" :data="acquired" border style="width: 100%; margin-top: 15px">
        <el-table-column prop="des_val" label="descripcion" width="950"></el-table-column>
        <el-table-column prop="cantidad" label="cantidad" width="90" align="right"></el-table-column>
        <el-table-column prop="pre_uni" label="precio" width="90" align="right"></el-table-column>
        <el-table-column align="right" width="100" fixed="right">
          <template slot-scope="scope" v-if="scope.row.compuesto === 'U' || scope.row.compuesto === 'B'">
            <el-button @click="initRemoveValues(scope.$index, scope.row)" type="danger" size="mini">Quitar
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <div v-if="!isSmallDevice" style="text-align: right; float: right">
        <h4></h4>
        <el-tag type="primary">EL IMPORTE TOTAL QUE DEBE CANCELAR ES: {{ total }}</el-tag>
        <h4></h4>
        <el-button type="primary" size="medium" @click="setValuesAcquired()">guardar la solicitud de valores
        </el-button>
        <h4></h4>
      </div>

      <!-- Diseño responsivo para dispositivos pequeños -->
      <div v-else class="responsive-container">
        <h4>valores universitarios disponibles</h4>
        <div v-for="(row, index) in offered" :key="`uno-${index}`" class="responsive-row">
          <div class="responsive-item">
            <div class="item-title">Descripcion</div>
            <div class="item-content">{{ row.des_val }}</div>
          </div>
          <div class="responsive-item">
            <div class="item-title">cantidad</div>
            <div class="item-content">{{ row.cantidad }}</div>
          </div>
          <div class="responsive-item">
            <div class="item-title">Importe</div>
            <div class="item-content">{{ row.pre_uni }}</div>
          </div>
          <div class="responsive-item">
            <div class="item-title">Acciones</div>
            <div class="item-content">
              <el-button type="primary" size="mini" @click="initAddValues(index, row)">Agregar</el-button>
            </div>
          </div>
        </div>
        <h4>valores universitarios seleccionados</h4>
        <div v-for="(row, index) in acquired" :key="`dos-${index}`" class="responsive-row">
          <div class="responsive-item">
            <div class="item-title">Descripcion</div>
            <div class="item-content">{{ row.des_val }}</div>
          </div>
          <div class="responsive-item">
            <div class="item-title">cantidad</div>
            <div class="item-content">{{ row.cantidad }}</div>
          </div>
          <div class="responsive-item">
            <div class="item-title">Importe</div>
            <div class="item-content">{{ row.pre_uni }}</div>
          </div>
          <div class="responsive-item">
            <div class="item-title">Acciones</div>
            <div class="item-content" v-if="row.compuesto === 'U' || row.compuesto === 'B'">
              <el-button type="danger" size="mini" @click="initRemoveValues(index, row)">Quitar</el-button>
            </div>
          </div>
        </div>
        <h4>EL IMPORTE TOTAL QUE DEBE CANCELAR ES: {{ total }}</h4>
        <el-button type="primary" size="medium" @click="setValuesAcquired()">guardar la solicitud de valores en linea
        </el-button>
      </div>
    </el-card>
  </div>
</template>

<script>

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
      isSmallDevice: window.innerWidth <= 768,
    };
  },
  mounted() {
    this.getValuesOffered();
    window.addEventListener("resize", this.updateDeviceSize);
  },
  destroyed() {
    window.removeEventListener("resize", this.updateDeviceSize);
  },
  methods: {
    test() {
      alert("llamar al 74246032 para asistencia tecnica");
    },
    updateDeviceSize() {
      this.isSmallDevice = window.innerWidth <= 768;
    },
    //  *  T1. Obtener los valores para la venta en linea
    async getValuesOffered() {
      console.log(this.client);
      var app = this;
      try {
        let response = await axios.post("/api/getValuesOffered", {
          year: app.client.gestion,
          typea: 'Sale',
          client: app.client,
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
            marker: "Sale",
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
.alert-space {
  margin-bottom: 15px;
}

.header-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.add-request-btn {
  align-self: center;
}

.alerts-container {
  margin-bottom: 20px;
}

.responsive-container {
  display: flex;
  flex-direction: column;
  gap: 15px;
  /* Espacio entre las filas */
}

.responsive-row {
  display: flex;
  flex-direction: column;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 5px;
  background-color: white;
  gap: 10px;
  /* Espacio entre los elementos */
}

.responsive-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 5px;
}

.item-title {
  font-weight: bold;
  color: #555;
  flex: 1;
}

.item-content {
  flex: 2;
}

.pagination {
  margin-top: 15px;
  text-align: right;
}

@media (max-width: 768px) {
  .header-container {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }

  .responsive-row {
    margin-bottom: 10px;
  }

  .responsive-item {
    flex-direction: row;
    gap: 10px;
  }

  .alerts-container {
    margin-bottom: 30px;
    /* Espacio extra para dispositivos pequeños */
  }

  .header {
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
  }

  .card-title {
    margin-bottom: 10px;
  }
}
</style>