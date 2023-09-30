<template>
  <div>
    <el-container>
      <el-header>
        <span>SISTEMA DE INFORMACION ADMINISTRATIVA Y FINANCIERA</span>
        <el-button @click="initLogin">LOGIN</el-button>
      </el-header>
      <el-container>
        <el-main>
          <!--        
      <el-row :gutter="20">
            <el-col :offset="4" :span="16">
              <router-view></router-view>
            </el-col>
          </el-row>
          <el-row :gutter="20">
            <el-col :offset="4" :span="4">
              <el-card>
                <el-button type="text" icon="el-icon-message" circle @click.native="initLogin"></el-button>
                <h4>valores en linea</h4>
                <p>
                  modulo para la venta de valores en linea
                </p>
              </el-card>
            </el-col>
            <el-col :span="4">
              <el-card>
                <el-button type="text" icon="el-icon-edit" circle @click.native="initLogin"></el-button>
                <h4>tramites</h4>
                <p>
                  modulo para la solicitud de tramite
                </p>
              </el-card>
            </el-col>
            <el-col :span="4">
              <el-card>
                <el-button type="text" icon="el-icon-document" circle @click.native="initLogin"></el-button>
                <h4>solicitud de memorial</h4>
                <p>
                  modulo para la solicitud de memorial
                </p>
              </el-card>
            </el-col>
            <el-col :span="4">
              <el-card>
                <el-button type="text" icon="el-icon-s-order" circle @click.native="initLogin"></el-button>
                <h4>solvencias</h4>
                <p>
                  modulo para la solicitud de solvencia
                </p>
              </el-card>
            </el-col>
          </el-row>
      -->
          <el-row>
            <router-view></router-view>
          </el-row>
        </el-main>
        <el-footer>
          <el-row :gutter="20">
            <el-col :span="8">
              <h5>ministerio de economia y finanzas</h5>
              <p>
              <ul>
                <li>
                  Curso de Pagos Electrónicos para Entidades Territoriales Autónomas y Universidades
                </li>
                <li>
                  sistema de gestion gubernamental SIGEP
                </li>
              </ul>
              </p>
            </el-col>
            <el-col :span="8">
              <h5>universidad autonoma tomas frias</h5>
              <p>
              <ul>
                <li>
                  <a href="http://academico.uatf.edu.bo/">
                    paginal principal
                  </a>
                </li>
                <li>
                  <a href="https://mail.uatf.edu.bo/">
                    correo institucional
                  </a>
                </li>
              </ul>
              </p>
            </el-col>
            <el-col :span="8">
              <h5>otros enlaces de interes</h5>
              <ul>
                <li>
                  <a href="https://www.economiayfinanzas.gob.bo">
                    ministerio de economía y finanzas bolivia
                  </a>
                </li>
                <li>
                  <a href="https://adsib.gob.bo/portal_frontend/">
                    adsib bolivia
                  </a>
                </li>
                <li>
                  <a href="https://www.contraloria.gob.bo/portal/">
                    Contraloría General del Estado - Bolivia
                  </a>
                </li>
              </ul>
            </el-col>
          </el-row>
          <el-divider></el-divider>
          <span>© UATF. Todos los derechos reservados.</span>
        </el-footer>
      </el-container>
    </el-container>
  </div>
</template>

<script>

export default {
  name: "Bienvenido",
  data() {
    return {
      messages: {},
      data: {},
    };
  },
  mounted() { },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    initLogin() {
      this.$router.push({ name: "login" });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#app {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: left;
}

button,
input,
select,
textarea {
  font-family: inherit;
  font-size: inherit;
  line-height: inherit;
  color: inherit;
}

.el-aside {
  color: #010e1f;
}

.el-header {
  background: #010e1f;
  color: #fff;
  line-height: 60px;
}

.el-header span {
  float: left;
}

.el-header .el-button {
  line-height: 5px;
  margin-top: 15px;
  color: #000;
  float: right;
}

.el-footer {
  height: auto !important;
  background-color: #010e1f;
  color: #fff;
  float: left;
}

.el-row {
  margin-bottom: 20px;
}

.el-card .el-button {
  font-size: 5rem;
  color: #d02431;
  display: block;
  margin: 0 auto;
}

.el-card h4 {
  font-size: 2rem;
  margin: 10px;
  display: block;
  text-align: center;
}

.el-card p {
  font-size: 15px;
}

ul {
  list-style-type: none;
}

li {
  padding: 5px 0;
  box-sizing: border-box;
  display: flex;
}

a {
  color: cadetblue;
  outline: none;
  text-decoration: none;
  padding: 5px 5px 0;
}
</style>
