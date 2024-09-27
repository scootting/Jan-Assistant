<template>
  <div>
    <el-container>
      <el-header>
        <el-row :gutter="12">
          <el-col :span="6">
            <span>DIRECCION ADMINISTRATIVA Y FINANCIERA</span>
          </el-col>
          <el-col :span="12">
            <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" background-color="#010e1f"
              text-color="#fff" active-text-color="#ffd04b" style="border-bottom: 0 !important">
              <el-menu-item index="1">
                <i class="el-icon-setting"></i>
                <router-link :to="{ name: 'layout' }" tag="span">
                  Inicio
                </router-link>
              </el-menu-item>
              <el-submenu index="2">
                <template slot="title">
                  <i class="el-icon-notebook-2"></i>
                  <span>Publicaciones</span>
                </template>
                <el-menu-item index="2.1">
                  <i class="el-icon-document"></i>
                  <router-link :to="{ name: 'financialstatements' }" tag="span">
                    ESTADOS FINANCIEROS
                  </router-link>
                </el-menu-item>
                <el-menu-item index="2.2" tag="span">
                  <i class="el-icon-document"></i>
                  <router-link :to="{ name: 'layout' }" tag="span">
                    CIRCULARES
                  </router-link>
                </el-menu-item>
              </el-submenu>
              <el-menu-item index="3">
                <i class="el-icon-s-platform"></i>
                <router-link :to="{ name: 'login' }" tag="span">
                  pagos en linea
                </router-link>
              </el-menu-item>
              <el-submenu index="4">
                <template slot="title">
                  <i class="el-icon-document"></i>
                  <span>tramites</span>
                </template>
                <el-menu-item index="4.1">
                  <i class="el-icon-document"></i>
                  <router-link :to="{ name: 'login' }" tag="span">
                    SOLVENCIA UNIVERSITARIA
                  </router-link>
                </el-menu-item>
              </el-submenu>
            </el-menu>
          </el-col>
        </el-row>
        <div>
        </div>
      </el-header>
        <el-main>
          <!-- Añadimos margen superior para que el contenido no se solape con el header -->
          <el-row :gutter="20">
            <el-col :xs="24" :md="16" :offset="4">
              <router-view></router-view>
            </el-col>
          </el-row>
        </el-main>
      <!-- Footer -->
      <el-footer>
        <el-row :gutter="20">
          <el-col :xs="24" :md="8">
            <h5>Información</h5>
            <ul>
              <li>Ubicación: Calle Serrudo, casi esquina cívica</li>
              <li>Teléfono:</li>
            </ul>
          </el-col>
          <el-col :xs="24" :md="8">
            <h5>Universidad Autónoma Tomás Frías</h5>
            <ul>
              <li><a href="http://academico.uatf.edu.bo/">Página principal</a></li>
              <li><a href="https://mail.uatf.edu.bo/">Correo institucional</a></li>
            </ul>
          </el-col>
          <el-col :xs="24" :md="8">
            <h5>Otros enlaces de interés</h5>
            <ul>
              <li><a href="https://www.economiayfinanzas.gob.bo">Ministerio de Economía y Finanzas Bolivia</a></li>
              <li><a href="https://adsib.gob.bo/portal_frontend/">ADSIB Bolivia</a></li>
              <li><a href="https://www.contraloria.gob.bo/portal/">Contraloría General del Estado - Bolivia</a></li>
            </ul>
          </el-col>
        </el-row>
        <el-divider></el-divider>
        <span>© UATF. Todos los derechos reservados.</span>
      </el-footer>
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
      activeIndex: '1',
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
    handleSelect(key, keyPath) {
      console.log(key, keyPath);
    }
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

.el-header {
  background: #010e1f;
  color: #fff;
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

.el-footer ul {
  list-style-type: none;
}

.el-footer li {
  padding: 5px 0;
  box-sizing: border-box;
  display: flex;
}

.el-footer a {
  color: cadetblue;
  outline: none;
  text-decoration: none;
  padding: 5px 5px 0;
}

/* Ajustes en pantallas pequeñas */
@media (max-width: 768px) {
  .el-header {
    text-align: center;
  }
  .el-container{
    width: 100%;
  }
}
</style>
