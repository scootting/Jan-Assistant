<template>
  <div>
    <el-container>
      <el-header>
        <span>SISTEMA DE INFORMACION ADMINISTRATIVA Y FINANCIERA</span>
        <!--
        <el-button type="primary" icon="el-icon-eleme"  @click="initLogin" plain>acceso al sistema</el-button>
        -->
        <el-button @click="initLogin">LOGIN</el-button>
      </el-header>
      <el-container>
        <!--
        <el-aside width="200px">Barra lateral</el-aside>
        -->
        <el-container>
          <el-main>
            <el-row :gutter="20">
              <el-col :offset="4" :span="16">
                <router-view></router-view>
              </el-col>
            </el-row>
            <el-row :gutter="20">
              <el-col :offset="4" :span="4">
                <el-card>
                  <el-button
                    type="text"
                    icon="el-icon-message"
                    circle
                    @click.native="initAddTaxExemption"
                  ></el-button>
                  <h4>facilito</h4>
                  <p>
                    modulo de pruebas
                  </p>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card>
                  <el-button
                    type="text"
                    icon="el-icon-edit"
                    circle
                    @click.native="initToFormalities"
                  ></el-button>
                  <h4>tramites</h4>
                  <p>
                    modulo de pruebas
                  </p>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card>
                  <el-button
                    type="text"
                    icon="el-icon-document"
                    circle
                    @click.native="initToDocuments"
                  ></el-button>
                  <h4>certificados</h4>
                  <p>
                    modulo de pruebas
                  </p>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card>
                  <el-button type="text" icon="el-icon-thumb" circle @click.native="LoginToClients"></el-button>
                  <h4>clientes</h4>
                  <p>
                    modulo de pruebas
                  </p>
                </el-card>
              </el-col>
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
                      sigep
                    </li>
                  </ul>
                </p>
              </el-col>
              <el-col :span="8">
                <h5>universidad autonoma tomas frias</h5>
                <p>
                  <ul>
                    <li>
                      pagina principal
                    </li>
                    <li>
                      correo institucional
                    </li>
                  </ul>
                </p>
              </el-col>
              <el-col :span="8">
                <h5>otros enlaces de interes</h5>
                  <!--
                <p>
                  Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus
                  ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa
                  justo sit amet risus. Etiam porta sem malesuada magna mollis euismod.
                  Donec sed odio dui.
                </p>
                <el-row>
                  <el-button>Default</el-button>
                  <el-button size="medium">Medium</el-button>
                  <el-button size="small">Small</el-button>
                  <el-button size="mini">Mini</el-button>
                </el-row>
                  -->
              </el-col>
            </el-row>
            <el-divider></el-divider>
            <span>© UATF. Todos los derechos reservados.</span>
          </el-footer>
        </el-container>
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
  mounted() {},
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    initLogin() {
      this.$router.push({ name: "login" });
    },
    initAddTaxExemption() {
      //link.href = window.URL.createObjectURL(blob);
      //link.download = "test.pdf";
      //link.click();
      this.$router.push({ name: "addTaxExemption" });
    },
    initToFormalities() {
      this.$router.push({ name: "formalities" });

      //PDF file is stored under project/public/download/info.pdf

      /*
    $file= public_path(). "/download/info.pdf";
    $headers = array(
              'Content-Type: application/pdf',
            );
    return Response::download($file, 'filename.pdf', $headers);
      */
    },
    initToDocuments() {
      this.$router.push({ name: "addnotdocument" });
    },
    LoginToClients() {
      this.$router.push({ name: "loginclient" });
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
  counter-reset: index;
  padding: 0;
  max-width: 1000px;
}

/* List element */
li {
  counter-increment: index;
  display: flex;
  align-items: center;
  padding: 5px 0;
  min-height: 60px;
  box-sizing: border-box;
}

/* Element counter */
li::before {
  content: counters(index, ".", decimal-leading-zero);
  font-size: 1.5rem;
  text-align: right;
  font-weight: bold;
  min-width: 50px;
  padding-right: 12px;
  font-variant-numeric: tabular-nums;
  align-self: flex-start;
  background-image: linear-gradient(to bottom, aquamarine, orangered);
  background-attachment: fixed;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* Element separation */
li + li {
  border-top: 1px solid rgba(255, 255, 255, 0.2);
}
</style>
