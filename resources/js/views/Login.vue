<template>
  <div id="app">
    <div class="container">
      <div class="instructions">
        PARA INGRESAR POR PRIMERA VEZ, DEBE COLOCAR COMO USUARIO SU NÚMERO DE IDENTIDAD, Y LA CONTRASEÑA ES SU FECHA DE
        NACIMIENTO SIN NINGÚN TIPO DE CARACTERES. EJEMPLO: 01/01/1990 DEBERÍA COLOCAR 01011990.
      </div>
      <div class="mobile-header">DIRECCIÓN ADMINISTRATIVA Y FINANCIERA</div>

      <div class="horizontal-layout">
        <div class="logo-container">
          <!--
          <img src="/mnt/data/portal2.PNG" alt="Logo" class="logo">
          url_image
          -->
          <el-image :src="url_image" alt="Logo" class="logo" style="width: 90%; height: 90%; margin-top: 20px;">
          </el-image>
        </div>

        <div class="login-container">
          <div class="login-header">LOGIN</div>
          <el-form :model="model" :rules="rules" @submit.native.prevent="login">
            <el-form-item>
              <el-input v-model="model.username" placeholder="Usuario">
                <i slot="prefix" class="el-input__icon el-icon-user"></i>
              </el-input>
            </el-form-item>
            <el-form-item>
              <el-input v-model="model.password" type="password" placeholder="Contraseña">
                <i slot="prefix" class="el-input__icon el-icon-lock"></i>
              </el-input>
            </el-form-item>
            <el-button type="primary" native-type="submit" block>Acceder</el-button>
          </el-form>
        </div>
      </div>

      <div class="footer-text">
        <el-button style="float: right; padding: 3px 0" type="text" @click.native="initRegisterPerson">Verifique si
          está
          registrado</el-button>
        <div style="color: gray; font-size: 12px;">Versión 2.02.02</div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "login",
  data() {
    return {
      model: {
        username: null,
        password: null,
      },
      dialogVisible: false,
      url_image: "/images/EUATF.png", //url('../images/EUATF.png'),//
      loading: false,
      error: null,
      rules: {
        username: [
          {
            required: true,
            message: "El usuario es requerido",
            trigger: "blur",
          },
          {
            min: 4,
            message: "El usuario de tener por lo menos 5 caracteres",
            trigger: "blur",
          },
        ],
        password: [
          {
            required: true,
            message: "La contraseña es requerida",
            trigger: "blur",
          },
          {
            min: 5,
            message: "La contraseña de tener por lo menos 5 caracteres",
            trigger: "blur",
          },
        ],
      },
    };
  },
  methods: {
    login() {
      this.$store
        .dispatch("retrieveToken", {
          username: this.model.username,
          password: this.model.password,
        })
        .then((response) => {
          this.$router.push({ name: "welcome" });
        })
        .catch((error) => {
          this.error = error.response.data;
          this.$notify.error({
            title: "Error",
            message: this.error.message,
          });
        });
    },
    initRegisterPerson() {
      this.$router.push({ name: "register" });
    },

  },
};
</script>
<style scoped>
body {
  margin: 0;
  background-color: #d9dbd4;
  font-family: Arial, sans-serif;
}

.container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  flex-direction: column;
}

.logo-container {
  display: flex;
  justify-content: center;
  margin-bottom: 20px;
}

.logo {
  width: 250px;
  height: 250px;
}

.login-container {
  width: 100%;
  max-width: 800px;
  /* Adjusted width for alignment */
  padding: 20px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.login-header {
  text-align: center;
  font-size: 16px;
  font-weight: bold;
  margin-bottom: 15px;
}

.mobile-header {
  display: none;
  font-size: 20px;
  font-weight: bold;
  color: #001f3f;
  text-align: center;
  margin: 50px 0px;
}

.horizontal-layout {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 50px;
  margin-bottom: 10px;
  width: calc(600px + 200px);
  /* Dynamic width based on instructions */
}

.footer-text {
  text-align: center;
  margin-top: 10px;
}

.instructions {
  background-color: #fce4e4;
  color: #d32f2f;
  padding: 10px;
  width: 100%;
  max-width: 600px;
  margin-bottom: 20px;
  text-align: center;
  border-radius: 4px;
  font-size: 14px;
  box-sizing: border-box;
}

@media (min-width: 601px) {
  .login-container {
    max-width: 800px;
    /* Matching width with adjusted layout */
  }

  .horizontal-layout {
    width: calc(600px + 200px);
    /* Ensures width includes instructions width + 200px */
  }
}

@media (max-width: 600px) {
  .logo-container {
    display: none;
  }

  .horizontal-layout {
    flex-direction: column;
    width: 88%;
  }

  .mobile-header {
    display: block;
  }

  .login-container {
    margin: 5px;
    /* 5px above, below, and on the sides */
    width: calc(100% - 10px);
    /* Full width minus margins */
  }
}
</style>
