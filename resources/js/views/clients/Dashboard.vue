<template>
  <div>
    <el-container>
      <el-aside
        width="260px"
        style="background-color: #010e1f; min-height: 100vh"
      >
        <div class="logo">
          <p>SISTEMA DE INFORMACION ADMINISTRATIVA Y FINANCIERA</p>
        </div>
        <el-menu
          default-active="1"
          class="el-menu-vertical-demo"
          background-color="#010e1f"
          text-color="#faebd7"
          active-text-color="#ffd04b"
          style="border-right: 0 !important"
        >
          <el-menu-item index="1">
            <i class="el-icon-document"></i>
            <router-link :to="{ name: 'welcome2' }" tag="span">
              inicio
            </router-link>
          </el-menu-item>
          <el-menu-item index="2">
            <i class="el-icon-menu"></i>
            <router-link :to="{ name: 'addnotdocument2' }" tag="span">
              certificado de no deudas
            </router-link>
          </el-menu-item>
          <el-menu-item index="3" disabled>
            <i class="el-icon-setting"></i>
            <span>solvencia universitaria</span>
          </el-menu-item>
        </el-menu>
      </el-aside>
      <el-container>
        <el-main style="padding: 40px; background: fff">
          <el-row type="flex" class="row-bg" justify="center">
            <el-col :span="22">
              <div id="level">
                <p></p>
                <div id="right-button">
                  <el-button
                    icon="el-icon-back"
                    circle
                    @click.native="initToWelcomePage"
                    warning
                  ></el-button>
                </div>
              </div>
              <br />
              <router-view></router-view>
            </el-col>
          </el-row>
        </el-main>
      </el-container>
    </el-container>
  </div>
</template>

<script>
export default {
  name: "app",
  data() {
    return {
      centerDialogVisible: false,
      //user: this.$store.state.user,
      //years: {},
      //options: {},
    };
  },
  mounted() {
    let app = this;
  },
  methods: {
    NoDeveloped() {
      this.$notify.warning({
        title: "advertencia",
        message: "aun el modulo no se ha desarrollado.",
      });
    },
    initToWelcomePage() {
      this.$router.push({
        name: "welcome2",
      });
    },
  },
};
</script>

<style>
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
  color: #212120;
}

/* estilos revisados y aprobados*/
.el-header {
  color: #fff;
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

.logo {
  padding: 42px 5px;
  align-content: center;
  text-align: center;
  color: #faebd7;
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
  font-size: 20px !important;
}

#right-button {
  align-items: right;
  justify-content: flex-end;
}
</style>
