<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>solicitud de memorial universitario</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row :gutter="20">
                <p>
                    <el-alert title="importante" type="warning"
                        description="antes de realizar la solicitud de memorial universitario debe estar seguro que su informacion personal se encuentra actualizada, puede verificarlo haciendo click en la opcion'INFORMACION PERSONAL' del menu."
                        show-icon>
                    </el-alert>
                </p>
                <el-col :span="12">
                    <div class="grid-content bg-purple">tipo
                        <p>solicitudes de memoriales que se pueden adquirir</p>
                        <el-table v-loading="loading" :data="dataMemorials" style="width: 100%">
                            <el-table-column label="solicitud" width="350">
                                <template slot-scope="scope">
                                    <span>solicitud de memorial para {{ scope.row.tipo }}</span>
                                </template>
                            </el-table-column>
                            <el-table-column align="right">
                                <template slot-scope="scope">
                                    <el-button @click="initAddValues(scope.$index, scope.row)" type="primary"
                                        size="mini" plain>Agregar
                                    </el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </el-col>
                <el-col :span="12">
                    <div class="grid-content bg-purple">
                        <p>memoriales solicitados</p>
                        <el-table :data="acquired" style="width: 100%">
                            <el-table-column label="solicitud" width="350">
                                <template slot-scope="scope">
                                    <span>solicitud de memorial para {{ scope.row.tipo }}</span>
                                </template>
                            </el-table-column>
                            <el-table-column align="right">
                                <template slot-scope="scope">
                                    <el-button @click="initRemoveValues(scope.$index, scope.row)" type="primary"
                                        size="mini" plain>Quitar
                                    </el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col :span="24">
                    <el-col :span="12">
                        <div class="grid-content bg-purple">
                            <p>informacion adicional que debe brindar para realizar su memorial</p>
                            <el-form ref="form" :model="this.adicional" label-width="200px" size="mini">
                                <div v-show=adicional.carrera.visible>
                                    <el-form-item label="nombre de la carrera">
                                        <el-input v-model="adicional.requisito1"></el-input>
                                    </el-form-item>
                                </div>
                                <div v-show=adicional.egreso.visible>
                                    <el-form-item label="gestion de egreso">
                                        <el-input v-model="adicional.requisito2"></el-input>
                                    </el-form-item>
                                </div>
                                <div v-show=adicional.profesion.visible>
                                    <el-form-item label="profesion actual">
                                        <el-input v-model="adicional.requisito3"></el-input>
                                    </el-form-item>
                                </div>
                            </el-form>
                        </div>
                    </el-col>
                    <br>
                    <div style="text-align: right; float: right">
                        <el-button type="primary" size="small" @click="setMemorialsAcquired()">guardar la solicitudes
                            seleccionadas
                        </el-button>
                    </div>
                </el-col>
            </el-row>
        </el-card>
    </div>
</template>
  
<script>

export default {
    name: "",
    data() {
        return {
            client: this.$store.state.user,
            loading: true,
            dataMemorials: [],
            acquired: [],
            adicional: {
                carrera: { descripcion: '', visible: '' },
                egreso: { descripcion: '', visible: '' },
                profesion: { descripcion: '', visible: '' },
            },
        };
    },
    mounted() {
        this.getTypesOfMemorials();
        this.adicional.carrera.visible = false;
        this.adicional.egreso.visible = false;
        this.adicional.profesion.visible = false;
    },
    methods: {
        test() {
            alert("llamar al 74246032 para asistencia tecnica");
        },
        //  *  T1. Obtener los valores para la venta en linea
        async getTypesOfMemorials() {
            var app = this;
            try {
                let response = await axios.post("/api/getTypesOfMemorials/", {
                    year: app.client.gestion,
                });
                app.loading = false;
                app.dataMemorials = response.data;
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        //  *  T2. Guardar los memoriales adquiridos
        async setMemorialsAcquired() {
            var app = this;
            console.log(app.acquired);
            try {
                if (app.acquired.length <= 0) {
                    this.$alert('debe seleccionar por lo menos una solicitud', 'HA OCURRIDO UN ERROR', {
                        confirmButtonText: 'BUENO',
                    });
                } else {
                    let response = await axios.post("/api/storeRequestMemorial/", {
                        client: app.client,
                        acquired: app.acquired,
                        marker: "MEMO",
                    });
                    this.$alert('ACABA DE CREAR UNA NUEVA SOLICITUD, PUEDE REALIZAR LA IMPRESION DE SU SOLICITUD', 'LO HA CONSEGUIDO', {
                        confirmButtonText: 'BUENO',
                    });
                    console.log(response);
                    //let id = response.data[0].ff_nueva_solicitud;
                    this.$router.push({
                        name: "requestmemorial",
                    });
                }
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        // * FUNLOCAL. agregar valores que se van a comprar
        initAddValues(index, row) {
            this.initVisibleRequeriments(row);
            this.acquired.push(row);
            console.log(this.client);
            console.log(this.acquired);
        },
        // * FUNLOCAL. Quitar valores que se iban a comprar
        initRemoveValues(index, row) {
            this.acquired.splice(index, 1);
            this.initRemoveRequeriments(row);

        },
        initVisibleRequeriments(row) {
            let app = this;
            switch (row.idx) {
                case 1:
                case 2:
                case 3:
                    app.adicional.carrera.visible = true;
                    break;
                case 4:
                    app.adicional.carrera.visible = true;
                    app.adicional.egreso.visible = true;
                    break;
                case 5:
                    app.adicional.profesion.visible = true;
                    break;
                default:
                    break;
            }
        },
        initRemoveRequeriments(row) {
            let app = this;
            switch (row.idx) {
                case 1:
                case 2:
                case 3:
                    app.adicional.carrera.visible = false;
                    break;
                case 4:
                    app.adicional.carrera.visible = false;
                    app.adicional.egreso.visible = false;
                    break;
                case 5:
                    app.adicional.profesion.visible = false;
                    break;
                default:
                    break;
            }
            this.acquired.forEach(element => {
                app.initVisibleRequeriments(element)
            });
        }

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
</style>
  