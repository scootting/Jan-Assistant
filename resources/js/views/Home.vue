<template>
  <div>
    <el-container>
      <el-header>
        <span>sistema de informacion administrativa y financiera</span>
        <!--
        <el-button type="primary" icon="el-icon-eleme"  @click="initLogin" plain>acceso al sistema</el-button>
        -->
        <el-button type="text" icon="el-icon-s-platform" @click="initLogin">acceso al sistema</el-button>
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
                  <!--
                  <el-button type="text" icon="el-icon-message" circle plain></el-button>
                  -->
                  <el-button type="text" icon="el-icon-message" circle></el-button>
                  <h4>facilito</h4>
                  <p>
                    Donec id elit non mi porta gravida at
                    eget metus. Fusce dapibus, tellus ac
                    cursus commodo, tortor mauris
                    condimentum nibh, ut fermentum massa
                    justo sit amet risus. Etiam porta sem
                    malesuada magna mollis euismod. Donec
                    sed odio dui.
                  </p>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card>
                  <el-button type="text" icon="el-icon-edit" circle></el-button>
                  <h4>funcion 1</h4>
                  <p>
                    Donec id elit non mi porta gravida at
                    eget metus. Fusce dapibus, tellus ac
                    cursus commodo, tortor mauris
                    condimentum nibh, ut fermentum massa
                    justo sit amet risus. Etiam porta sem
                    malesuada magna mollis euismod. Donec
                    sed odio dui.
                  </p>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card>
                  <el-button type="text" icon="el-icon-check" circle></el-button>
                  <h4>funcion 2</h4>
                  <p>
                    Donec id elit non mi porta gravida at
                    eget metus. Fusce dapibus, tellus ac
                    cursus commodo, tortor mauris
                    condimentum nibh, ut fermentum massa
                    justo sit amet risus. Etiam porta sem
                    malesuada magna mollis euismod. Donec
                    sed odio dui.
                  </p>
                </el-card>
              </el-col>
              <el-col :span="4">
                <el-card>
                  <el-button type="text" icon="el-icon-delete" circle></el-button>
                  <h4>funcion 3</h4>
                  <p>
                    Donec id elit non mi porta gravida at
                    eget metus. Fusce dapibus, tellus ac
                    cursus commodo, tortor mauris
                    condimentum nibh, ut fermentum massa
                    justo sit amet risus. Etiam porta sem
                    malesuada magna mollis euismod. Donec
                    sed odio dui.
                  </p>
                </el-card>
              </el-col>
            </el-row>
          </el-main>
          <el-footer>
            <el-row :gutter="20">
              <el-col :span="8">
                <h5>Heading 1</h5>
                <p>
                  Donec id elit non mi porta gravida at
                  eget metus. Fusce dapibus, tellus ac
                  cursus commodo, tortor mauris
                  condimentum nibh, ut fermentum massa
                  justo sit amet risus. Etiam porta sem
                  malesuada magna mollis euismod. Donec
                  sed odio dui.
                </p>
              </el-col>
              <el-col :span="8">
                <h5>Heading 2</h5>
                <p>
                  Donec id elit non mi porta gravida at
                  eget metus. Fusce dapibus, tellus ac
                  cursus commodo, tortor mauris
                  condimentum nibh, ut fermentum massa
                  justo sit amet risus. Etiam porta sem
                  malesuada magna mollis euismod. Donec
                  sed odio dui.
                </p>
              </el-col>
              <el-col :span="8">
                <h5>Heading 3</h5>
                <p>
                  Donec id elit non mi porta gravida at
                  eget metus. Fusce dapibus, tellus ac
                  cursus commodo, tortor mauris
                  condimentum nibh, ut fermentum massa
                  justo sit amet risus. Etiam porta sem
                  malesuada magna mollis euismod. Donec
                  sed odio dui.
                </p>
                <el-row>
                  <el-button>Default</el-button>
                  <el-button size="medium">Medium</el-button>
                  <el-button size="small">Small</el-button>
                  <el-button size="mini">Mini</el-button>
                </el-row>
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
      data: {}
    };
  },
  mounted() {},
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    initLogin() {
      this.$router.push({ name: "login" });
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
/*
@import url("https://fonts.googleapis.com/css?family=Roboto+Condensed");
  font-family: 'Chelsea Market', cursive !important;
  font-weight: 400 !important;
  font-family: 'Chelsea Market', cursive !important;
 */
html,
body {
}
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
  color: #333;
}

.el-header {
  background-color: #eeeeee;
  background: #ffffff;
  color: #333;
  line-height: 60px;
}
.el-header span {
  float: left;
}
.el-header .el-button {
  line-height: 5px;
  margin-top: 10px;
  float: right;
}

.el-footer {
  height: auto !important;
  background-color: #111111;
  color: #858585;
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
.el-card h4{
  font-size: 2rem;
  margin: 10px;
  display: block;
  text-align: center;
}
.el-card p{
  font-size: 15px;
}
</style>
