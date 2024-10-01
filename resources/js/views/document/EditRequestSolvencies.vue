<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>soicitud de solvencia universitaria</span>
            </div>
            <el-row>
                <el-col :span="16">
                    <div class="grid-content bg-purple">
                        <el-form :model="document" label-width="300px" size="mini">
                            <el-form-item label="concepto">
                                {{ document.des_tipo }}
                            </el-form-item>
                            <el-form-item label="unidad academica o administrativa">
                                {{ document.des_prg }}
                            </el-form-item>
                            <el-form-item label="numero de carnet">
                                {{ document.ci_per }}
                            </el-form-item>
                            <el-form-item label="apellidos y nombres">
                                {{ document.des_per }}
                            </el-form-item>
                            <el-form-item label="fecha del registro">
                                <el-date-picker type="date" v-model="document.fecha" placeholder="seleccione una fecha"
                                    style="width: 100%" format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
                            </el-form-item>
                            <el-form-item label="direccion">
                                <el-input v-model="document.direccion"></el-input>
                            </el-form-item>
                            <el-form-item label="telefono/celular">
                                <el-input v-model="document.tel_per"></el-input>
                            </el-form-item>
                            <el-form-item label="correo electronico">
                                <el-input v-model="document.correo"></el-input>
                            </el-form-item>
                            <el-form-item label="estado">
                                <el-tag type="" effect="dark">{{ document.estado }}</el-tag>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-col>
            </el-row>
            <el-button type="success" size="small" @click="storeDataSolvency()">actualizar informacion</el-button>
            <el-button type="primary" size="small" @click="initPrintSolvency()">imprimir solicitud</el-button>
        </el-card>
    </div>
</template>
<script>
export default {
    name: "",
    data() {
        return {
            client: this.$store.state.user,
            id: this.$route.params.id,
            document: {},
        };
    },
    mounted() {
        this.getDataSolvencyById();
    },
    methods: {
        test() {
            alert("bienvenido al modulo");
        },

        //  *  D3. Obtener la informacion por cada solicitud
        //  * {id: id de la solicitud }
        async getDataSolvencyById() {
            var app = this;
            try {
                let response = await axios.post("/api/getDataSolvencyById", {
                    id: app.id,
                    user: app.client,
                })
                app.document = response.data[0];
                console.log(response.data[0]);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        async storeDataSolvency() {
            var app = this;
            console.log(app.document);
            try {
                let response = await axios.post("/api/storeDataSolvency", {
                    cliente: app.client,
                    solvencia: app.document,
                    marker: "editar",
                });
                console.log(response);
                alert("Datos Actualizados correctamente");
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        //  *  M6. Imprimir la solvencia en linea
        async initPrintSolvency() {
            let app = this;
            axios({
                url: "/api/printDocumentSolvency",
                params: {
                    id: app.document.id,
                    gestion: app.document.gestion,
                },
                method: "GET",
                responseType: "arraybuffer",
            }).then((response) => {
                let blob = new Blob([response.data], {
                    type: "application/pdf",
                });
                let link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                let url = window.URL.createObjectURL(blob);
                window.open(url);
            });
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

.el-input__inner {
    color: #000 !important;
}

</style>