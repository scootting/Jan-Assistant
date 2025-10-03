<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>informacion personal</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row :gutter="20">
                <p>
                    <el-alert
                        title="los cambios se efectuaran para la siguiente vez que ingrese al sistema."
                        type="success">
                    </el-alert>
                </p>
                <el-col :xs="24" :md="12">
                    <div class="grid-content bg-purple">
                        <p>datos personales</p>
                        <el-form ref="form" :model="this.pass" label-width="250px" size="mini">
                            <el-form-item label="contraseña actual">
                                <el-input v-model="pass.actual" type="password"></el-input>
                            </el-form-item>
                            <el-form-item label="nueva contraseña">
                                <el-input v-model="pass.nuevo" type="password"></el-input>
                            </el-form-item>
                            <el-form-item label="confirme la nueva contraseña">
                                <el-input v-model="pass.confirma" type="password"></el-input>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-col>
            </el-row>
            <div class="acciones">
                <el-button type="warning" size="small" @click="updatePersonPassword()">cambiar contraseña</el-button>
            </div>
        </el-card>
    </div>
</template>

<script>
export default {
    name: "",
    data() {
        return {
            client: this.$store.state.user,
            pass: {
                actual: '',
                nuevo: '',
                confirma: '',
            },
        };
    },
    methods: {
        async updatePersonPassword() {
            var app = this;
            try {
                let response = await axios.post("/api/updatePersonPassword", {
                    id: app.client.nodip,
                    actual: app.pass.actual,
                    nuevo: app.pass.nuevo,
                    confirma: app.pass.confirma,
                });
                app.$alert("se ha actualizado la contraseña correctamente!!! ... la siguiente vez que inicie sesion debera usar su nueva contraseña", "Gestor de mensajes", {
                    dangerouslyUseHTMLString: true,
                });
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
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
    padding: 15px;
    min-height: 36px;
}

.row-bg {
    padding: 10px 0;
    background-color: #f9fafc;
}

.acciones {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

/* Para pantallas pequeñas */
@media (max-width: 768px) {

    /* 🔥 fuerza layout vertical en móviles */
    .el-form-item {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
    }

    /* 🔥 sobrescribe ancho fijo del label */
    .el-form-item__label {
        width: 100% !important;
        display: block !important;
        text-align: left !important;
        padding-bottom: 6px !important;
        margin-bottom: 0 !important;
    }

    /* 🔥 asegura que el input ocupe todo el ancho */
    .el-form-item__content {
        width: 100% !important;
        display: block !important;
        margin-left: 0 !important;
    }

    .el-button {
        width: 100% !important;
        margin-bottom: 10px !important;
        margin-left: 0 !important;
    }

    /* 🔥 Si los botones están en un contenedor horizontal, lo ponemos vertical */
    .el-form-item__content {
        flex-direction: column !important;
        align-items: stretch !important;
    }

}
</style>