<template>
  <div id="app">
    <div class="header">
      <div>
        <span>Sistema de Información Administrativa y Financiera</span>
      </div>
      <div class="name">
        <!--
        <span>CALLIZAYA COSSIO, REMY LIONEL</span>
        -->
        <el-dropdown>
          <span class="el-dropdown-link">
            <span>
              {{ client.descripcion }}
              <!--
              <i class="el-icon-arrow-down el-icon--right"></i>
              -->
            </span>
            <i class="el-icon-arrow-down el-icon--right"></i>
          </span>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item icon="el-icon-user" @click.native="initToShowClient">Perfil</el-dropdown-item>
            <el-dropdown-item icon="el-icon-right" @click.native="logoutClient">Cerrar sesión</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </div>
    </div>

    <div class="toggle-menu" @click="toggleMenu">Menú</div>

    <div class="main">
      <div class="sidebar" :class="{ open: isMenuOpen }">
        <div class="logo">universidad autonoma tomas frias </div>
        <el-menu class="menu">
          <!--
          <el-menu class="menu" router>
          -->
          <el-menu-item index="1">
            <i class="el-icon-document"></i>
            <router-link :to="{ name: 'welcome' }" @click="toggleMenu" tag="span">
              inicio
            </router-link>
          </el-menu-item>
          <el-menu-item index="2">
            <i class="el-icon-menu"></i>
            <router-link :to="{ name: 'information' }" @click="toggleMenu" tag="span">
              informacion personal
            </router-link>
          </el-menu-item>
          <el-menu-item index="3">
            <i class="el-icon-shopping-bag-1"></i>
            <router-link :to="{ name: 'requests' }" @click="toggleMenu" tag="span">
              compra de valores en linea
            </router-link>
          </el-menu-item>
          <el-menu-item index="4">
            <i class="el-icon-postcard"></i>
            <router-link :to="{ name: 'courses' }" @click="toggleMenu" tag="span">
              becas
            </router-link>
          </el-menu-item>
          <el-menu-item index="5">
            <i class="el-icon-menu"></i>
            <router-link :to="{ name: 'requestsolvencies' }" @click="toggleMenu" tag="span">
              Solvencia Universitaria
            </router-link>
          </el-menu-item>
          <!--
          <el-menu-item index="4">Venta de Matrículas en Línea</el-menu-item>
          <el-menu-item index="5">Memorial Universitario</el-menu-item>
          -->
        </el-menu>
      </div>
      <div class="content">
        <router-view></router-view>
      </div>
    </div>
  </div>

</template>

<script>
export default {
  name: "app",
  data() {
    return {
      error: "",
      isMenuOpen: false,
      client: this.$store.state.user,
      yearSelected: 1999,
      routesToCloseMenu: ['welcome', 'information', 'requests', 'courses', 'requestsolvencies'], // Rutas que deben cerrar el menú
    };
  },
  mounted() {
    let app = this;
    app.yearSelected = app.client.gestion;
    console.log(app.client);
  },

  watch: {
    $route(to) {
      if (this.routesToCloseMenu.includes(to.name)) {
        this.isMenuOpen = !this.isMenuOpen;
      }
    },
  },
  methods: {
    toggleMenu() {
      this.isMenuOpen = !this.isMenuOpen;
    },

    logoutClient() {
      this.$router.push({
        name: "logout",
      });
    },

    initToShowClient() {
      console.log("iniciar la informacion del cliente")
    },
    NoDeveloped() {
      this.$notify.warning({
        title: "advertencia",
        message: "aun el modulo no se ha desarrollado.",
      });
    },
    initToWelcomePage() {
      this.$router.push({
        name: "welcome",
      });
    },
  },
};
</script>

<style>
body {
  margin: 0;
  font-family: Arial, sans-serif;
  background-color: #e5e5dc;
}

#app {
  display: flex;
  flex-direction: column;
  height: 100vh;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #f3f3f3;
  padding: 10px 20px;
  border-bottom: 1px solid #ddd;
}

.header .name {
  text-align: center;
}

.sidebar {
  width: 250px;
  background-color: #2c3e50;
  color: white;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  transition: max-height 0.3s ease-in-out;
}

.sidebar .logo {
  text-align: center;
  padding: 20px;
  font-weight: bold;
  font-size: 18px;
  background-color: #34495e;
}

.menu {
  flex: 1;
  overflow-y: auto;
}

.menu-item {
  padding: 15px;
  cursor: pointer;
}

.menu-item:hover {
  background-color: #3a506b;
}

.toggle-menu {
  display: none;
  cursor: pointer;
  padding: 10px;
  background-color: #34495e;
  color: white;
  text-align: center;
}

.main {
  display: flex;
  flex: 1;
  overflow: hidden;
}

.content {
  flex: 1;
  padding: 20px;
  overflow-y: auto;
}

@media (max-width: 768px) {
  .header {
    flex-direction: column;
    align-items: center;
  }

  .header .name {
    margin-bottom: 10px;
  }

  .sidebar {
    max-height: 0;
    width: 100%;
  }

  .sidebar.open {
    max-height: 400px;
  }

  .toggle-menu {
    display: block;
  }

  .main {
    flex-direction: column;
  }

  .menu {
    overflow-y: hidden;
    /* Previene la barra de desplazamiento */
  }
}
</style>
