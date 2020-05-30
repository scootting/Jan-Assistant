<template>
  <div>
    <el-container>
      <el-header>
        <el-button>Default</el-button>
        <el-button size="medium" class="version">Medium</el-button>
        <el-button size="small">Small</el-button>
        <el-button size="mini">Mini</el-button>
        <el-button type="text">Text Button</el-button>
        <el-tag>Tag 1</el-tag>header
      </el-header>
      <!-- 
          style="margin:5px; padding:10px; border:0px"

      <el-header>
        <el-row>
          <el-col :span="20">
            <el-menu class="el-menu-demo" mode="horizontal">
              <el-menu-item index="1">DIRECCION ADMINISTRATIVA Y FINANCIERA</el-menu-item>
            </el-menu>
          </el-col>
          <el-col :span="4">
            <div style="text-align: right; background-color: #fff">
               <el-button type="success" plain>Acceso al sistema
                   
               </el-button>
            </div>
          </el-col>
        </el-row>
      </el-header>

      -->

      <el-container>
        <!--
        <el-aside width="200px">Barra lateral</el-aside>
        -->
        <el-container>
          <el-main>
            <el-row :gutter="20">
              <el-col :span="24">
                <el-card class="box-card">
                  <h1>Hello, world!</h1>
                  <p>
                    This is a template for a simple
                    marketing or informational website. It
                    includes a large callout called a
                    jumbotron and three supporting pieces of
                    content. Use it as a starting point to
                    create something more unique.
                  </p>
                  <el-button size="mini" plain>Default</el-button>
                  <el-button type="primary" size="mini" plain>Primary</el-button>
                </el-card>
              </el-col>
            </el-row>
            <el-row :gutter="20">
              <el-col :span="8">
                <el-card class="box-card">
                  <h5>Heading</h5>
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
              <el-col :span="8">
                <el-card class="box-card">
                  <h5>Heading</h5>
                  <p>
                    Donec id elit non mi porta gravida at
                    eget metus. Fusce dapibus, tellus ac
                    cursus commodo, tortor mauris
                    condimentum nibh, ut fermentum massa
                    justo sit amet risus. Etiam porta sem
                    malesuada magna mollis euismod. Donec
                    sed odio dui.
                  </p>
                  <el-button type="primary" icon="el-icon-edit"></el-button>
                  <el-button type="primary" icon="el-icon-share"></el-button>
                </el-card>
              </el-col>
              <el-col :span="8">
                <el-card class="box-card">
                  <h5>Heading</h5>
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
            <el-row :gutter="20">
              <el-col :span="4">
                <div class="grid-content bg-purple"></div>
              </el-col>
              <el-col :span="16">
                <div class="grid-content bg-purple"></div>
              </el-col>
              <el-col :span="4">
                <div class="grid-content bg-purple"></div>
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
  /*
  font-family: 'Open Sans Condensed', sans-serif !important;
  font-family: 'Open Sans Condensed', sans-serif !important;
  */
  color: #333;
}

.el-header {
  height: auto !important;
  background-color: #eeeeee;
  color: #333;
  text-align: center;
  line-height: 20;
}
  .version{
    line-height: 5 !important;
    background: #123234 !important;
  }

.el-footer {
  height: auto !important;
  background-color: #111111;
  color: #858585;
  text-align: left;
}
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
  min-height: 36px;
}
.row-bg {
  padding: 10px 0;
  background-color: #f9fafc;
}
</style>
