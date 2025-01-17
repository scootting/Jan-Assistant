<template>
    <div id="app">
        <!-- Header -->
        <div class="header">
            <div class="logo">DIRECCIÓN ADMINISTRATIVA Y FINANCIERA</div>
            <el-menu mode="horizontal" class="menu" :default-active="'1'" background-color="#001f3f"
                text-color="#ffffff" active-text-color="#FFD700" style="border-bottom: 0 !important">
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
                <el-menu-item index="5">Contacto</el-menu-item>
            </el-menu>
        </div>
        <div class="body">
            <router-view></router-view>
        </div>
        <!-- Footer -->
        <div class="footer">
            <!-- Links -->
            <div class="links">
                <div>
                    <h3>INFORMACIÓN</h3>
                    <p>Ubicación: Calle Serrudo casi esquina Cívica</p>
                    <p>Teléfono:</p>
                </div>
                <div>
                    <h3>UNIVERSIDAD AUTÓNOMA TOMÁS FRÍAS</h3>
                    <p><a href="http://uatf.edu.bo/">Página Principal</a></p>
                    <p><a href="https://mail.uatf.edu.bo/"">Correo Institucional</a></p>
                </div>
                <div>
                    <h3>OTROS ENLACES DE INTERÉS</h3>
                    <p><a href=" https://www.economiayfinanzas.gob.bo">Ministerio de Economía y Finanzas Bolivia</a>
                    </p>
                    <p><a href="https://adsib.gob.bo/portal_frontend/">ADSIB Bolivia</a></p>
                    <p><a href="https://www.contraloria.gob.bo/portal/">Contraloría General del Estado - Bolivia</a></p>
                </div>
            </div>
            <div class="info">
                © 2025 Dirección Administrativa y Financiera
            </div>
        </div>
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
body {
    margin: 0;
    font-family: Arial, sans-serif;
}

.header {
    background-color: #001f3f;
    color: white;
    padding: 10px 10px;
    text-align: center;
}

.logo {
    font-size: 1.5em;
    font-weight: bold;
    margin-bottom: 10px;
}

.menu {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.menu>* {
    margin: 5px;
}

@media (max-width: 768px) {
    .menu {
        flex-direction: column;
        align-items: center;
    }
}

.links {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    margin: 0px 20px;
    gap: 10px;
}

.links div {
    flex: 1 1 30%;
    min-width: 200px;
}

@media (max-width: 768px) {
    .links {
        flex-direction: column;
        align-items: stretch;
    }

    .links div {
        flex: 1 1 auto;
    }
}

.body{
    margin: 20px;
}

.footer {
    color: white;
    background-color: #001f3f;
}

.info {
    color: white;
    text-align: center;
    padding: 10px 0;
}

a {
    color: #FFD700;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
</style>