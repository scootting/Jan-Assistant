<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>informacion personal</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="test()">ayuda</el-button>
      </div>
      <el-row :gutter="20">
        <p>
          <el-alert
            title="El cambio de la direccion, telefono, correo implica que sus nuevas solicitudes tendrán esta nueva informacion."
            type="success">
          </el-alert>
        </p>
        <el-col :xs="24" :md="12">
          <div class="grid-content bg-purple">
            <p>datos personales</p>
            <el-form ref="form" :model="this.client" label-width="180px" size="mini" label-position="right">
              <el-form-item label="carnet de identidad">
                <el-input v-model="client.nodip" disabled></el-input>
              </el-form-item>
              <el-form-item label="apellido paterno">
                <el-input v-model="client.paterno" disabled></el-input>
              </el-form-item>
              <el-form-item label="apellido materno">
                <el-input v-model="client.materno" disabled></el-input>
              </el-form-item>
              <el-form-item label="nombres">
                <el-input v-model="client.nombres" disabled></el-input>
              </el-form-item>
              <el-form-item label="fecha de nacimiento">
                <el-input v-model="client.nacimiento" disabled></el-input>
              </el-form-item>
              <el-form-item label="sexo">
                <el-input v-model="client.sexo" disabled></el-input>
              </el-form-item>
            </el-form>
          </div>
        </el-col>
        <el-col :xs="24" :md="12">
          <div class="grid-content bg-purple">
            <p>datos adicionales</p>
            <el-form ref="form" :model="this.client" label-width="200px" size="mini">
              <el-form-item label="direccion">
                <el-input v-model="client.direccion"></el-input>
              </el-form-item>
              <el-form-item label="telefono/celular">
                <el-input v-model="client.telefono"></el-input>
              </el-form-item>
              <el-form-item label="correo electronico">
                <el-input v-model="client.correo"></el-input>
              </el-form-item>
            </el-form>
          </div>
        </el-col>
      </el-row>
      <div class="acciones">
        <el-button type="primary" size="small" @click="updatePersonInformation()">actualizar informacion</el-button>
        <el-button type="warning" size="small" @click="updatePersonPassword()">cambiar contraseña</el-button>
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
    };
  },
  mounted() {
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    async updatePersonInformation() {
      var app = this;
      try {
        let response = await axios.post("/api/storePersonInformation", {
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
      }
    },
    updatePersonPassword() {
      this.$router.push({
        name: "password",
      });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
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


.acciones {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

/* Para pantallas pequeñas */
@media (max-width: 768px) {

  /* 🔥 fuerza layout vertical en móviles */
  .el-form-item {
    display: flex !important;
    flex-direction: column !important;
    align-items: stretch !important;
  }

  /* 🔥 sobrescribe ancho fijo del label */
  .el-form-item__label {
    width: 100% !important;
    display: block !important;
    text-align: left !important;
    padding-bottom: 6px !important;
    margin-bottom: 0 !important;
  }

  /* 🔥 asegura que el input ocupe todo el ancho */
  .el-form-item__content {
    width: 100% !important;
    display: block !important;
    margin-left: 0 !important;
  }

  .el-button {
    width: 100% !important;
    margin-bottom: 10px !important;
    margin-left: 0 !important;
  }

  /* 🔥 Si los botones están en un contenedor horizontal, lo ponemos vertical */
  .el-form-item__content {
    flex-direction: column !important;
    align-items: stretch !important;
  }

}
</style>
