<template>
  <div>
    <el-container>
      <el-header style="text-align: right; background-color: #fff">
        <!---->
        <el-dropdown size="medium">
          <span class="el-dropdown-link">
            Configuracion
            <i class="el-icon-arrow-down el-icon--right"></i>
          </span>
          <el-dropdown-menu slot="dropdown" style="margin-left:10px">
            <el-dropdown-item icon="el-icon-date" @click.native="centerDialogVisible = true">gestion</el-dropdown-item>
            <el-dropdown-item icon="el-icon-setting" @click.native="NoDeveloped">perfiles</el-dropdown-item>
            <el-dropdown-item icon="el-icon-message" @click.native="NoDeveloped">mensajes</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
        <el-dropdown size="medium">
          <span class="el-dropdown-link">
            {{ user.descripcion
            }}
            <i class="el-icon-arrow-down el-icon--right"></i>
          </span>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item icon="el-icon-user" @click.native="NoDeveloped">mi perfil</el-dropdown-item>
            <el-dropdown-item icon="el-icon-chat-dot-square" @click.native="NoDeveloped">mis mensajes</el-dropdown-item>
            <el-dropdown-item icon="el-icon-right" @click.native="logoutUser">cerrar sesion</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </el-header>
      <el-container>
        <el-aside width="224px" style="background-color: #263f44; min-height:100vh">
          <!--                  
                *** - menu dinamico usando ElementUI - ***
          -->
          <el-menu
            default-active="2"
            class="el-menu-vertical-demo"
            background-color="#263f44"
            text-color="#fff"
            active-text-color="#fff"
            style="border-right: 0 !important"
          >
            <template v-for="(option, index) in options">
              <el-submenu :index="String(index)" :key="index">
                <template slot="title">
                  <i :class="option.icon"></i>
                  <span slot="title">
                    {{
                    option.title.trim()
                    }}
                  </span>
                </template>
                <el-menu-item-group
                  v-for="(group, g_index) in option.groups"
                  :key="g_index"
                  :title="group.title.trim()"
                >
                  <el-menu-item v-for="(item, i_index) in group.items" :key="i_index">
                    <!-- :index='String(g_index)'-->
                    <router-link :to="{ name: item.view.trim() }" tag="span">
                      {{
                      item.title.trim()
                      }}
                    </router-link>
                  </el-menu-item>
                </el-menu-item-group>
              </el-submenu>
            </template>
          </el-menu>
          <!--                  
                *** - finalizacion del menu dinamico usando ElementUI - ***
          -->
        </el-aside>
        <el-main style="padding:40px; color:#000">
          <el-row type="flex" class="row-bg" justify="center">
            <el-col :span="22">
              <div id="level">
                <el-breadcrumb separator-class="el-icon-arrow-right">
                  <el-breadcrumb-item>Api</el-breadcrumb-item>
                  <el-breadcrumb-item>Welcome</el-breadcrumb-item>
                </el-breadcrumb>
                <div id="right-button">
                  <el-button icon="el-icon-back" circle @click.native="ToWelcomePage"></el-button>
                </div>
              </div>
              <br />
              <router-view></router-view>
            </el-col>
          </el-row>
        </el-main>
      </el-container>
    </el-container>
    <!--                  
      *** - dialogo usando ElementUI - ***
    -->
    <el-dialog title="Warning" :visible.sync="centerDialogVisible" width="35%" center>
      <span>este mensaje esta colocado a proposito.</span>
      <el-form>
        <el-form-item label="gestion">
          <el-select
            v-model="yearSelected"
            placeholder="por favor seleccione una gestion"
            value-key="gestion"
          >
            <el-option
              v-for="item in years"
              :label="item.gestion"
              :value="item.ff_gestiones_usuario"
              :key="item.ff_gestiones_usuario"
            ></el-option>
          </el-select>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="centerDialogVisible = false">Cancelar</el-button>
        <el-button type="primary" @click="changeYear">Confirmar</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
export default {
  name: "app",
  data() {
    return {
      centerDialogVisible: false,
      error: "",
      user: this.$store.state.user,
      years: {},
      options: {},
      yearSelected: 1999 // this.user.gestion,
    };
  },
  created() {},
  mounted() {
    let app = this;
    app.yearSelected = app.user.gestion;
    axios
      .post("/api/profiles", {
        usuario: app.user.usuario,
        gestion: app.user.gestion
      })
      .then(response => {
        app.options = response.data;
        axios
          .post("/api/years", { usuario: app.user.usuario })
          .then(response => {
            app.years = response.data;
          })
          .catch(error => {
            this.error = error;
            this.$notify.error({
              title: "Error",
              message: this.error.message
            });
          });
      })
      .catch(error => {
        this.error = error;
        this.$notify.error({
          title: "Error",
          message: this.error.message
        });
      });
  },
  computed: {
  },
  methods: {
    logoutUser() {
      this.$router.push({ name: "logout" });
    },
    changeYear() {
      let app = this;
      console.log(app.yearSelected);
      axios
        .post("/api/profiles", {
          usuario: app.user.usuario,
          gestion: app.yearSelected
        })
        .then(response => {
          app.options = response.data;
          app.user.gestion = app.yearSelected;
          app.$store.commit("updateUser", app.user);
        })
        .catch(error => {
          this.error = error;
          this.$notify.error({
            title: "Error",
            message: this.error.message
          });
        });
      app.centerDialogVisible = false;
    },
    NoDeveloped() {
      this.$notify.warning({
        title: "advertencia",
        message: "aun el modulo no se ha desarrollado.",
        showClose: false
      });
    },
    ToWelcomePage(){
        this.$router.push({name: 'welcome'});
    }
  }
};
</script>

<style>
@import url("https://fonts.googleapis.com/css?family=Roboto+Condensed");
html,
body {
  font-family: "Roboto Condensed", sans-serif;
}
#app {
  font-family: "Roboto Condensed", sans-serif;
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

/* estilos revisados y aprobados*/
.el-header {
  color: #333;
  line-height: 60px;
  padding-left: 224px;
}

.el-dropdown {
  color: #000a;
  padding: 0px 18px;
}
.el-dropdown-link {
  cursor: pointer;
  margin-bottom: 20px;
}
.el-icon-arrow-down {
  font-size: 12px;
}
/* estilos revisados y aprobados para el card */
#level {
  display: flex !important;
  align-items: center;
  justify-content: space-between;
}
.el-breadcrumb {
  align-items: center;
  justify-content: flex-start;
  font-size: 22px !important;
}
#right-button {
  align-items: right;
  justify-content: flex-end;
}

/*
      options: [
            {
              "title" : "contabilidad",
              "icon"  : "el-icon-notebook-2",
              "groups": [
                {
                  "title" : "recursos propios",  
                  "items" :[
                    {
                      "view" : "assets",
                      "title": "activos fijos"
                    }
                  ]
                }
              ],
            },
            {
              "title" : "bienes e inventarios",
              "icon"  : "el-icon-notebook-2",
              "groups": [],
            },
            {
              "title" : "tesoro",
              "icon"  : "el-icon-back",
              "groups": [],
            },
            {
              "title" : "aplicacion",
              "icon"  : "el-icon-setting",
              "groups": [
                {
                  "title" : "recursos propios",  
                  "items" :[
                    {
                      "view" : "profiles",
                      "title": "perfiles"
                    },
                    {
                      "view" : "profiles",
                      "title": "lionel"
                    }
                  ]
                }
              ],
            },
          ]      
  */
/*
         * obtener el reporte generado desde REST API de JasperReport Server
         */
/*
        presionar(index) {
            let app = this;
            axios({
                url: "/reporte",
                method: "GET",
                responseType: "blob" // important
            }).then(response => {
                const blob = new Blob([response.data], {
                    type: "application/pdf"
                });
                const objectUrl = window.URL.createObjectURL(blob);
                console.log(objectUrl);
                window.open(objectUrl);
            });
        }*/
</style>
