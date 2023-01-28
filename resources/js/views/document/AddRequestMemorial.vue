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
                    <div class="grid-content bg-purple">
                        <p>solicitudes de memoriales que se pueden adquirir</p>
                        <el-table v-loading="loading" :data="dataMemorials" style="width: 100%">
                            <el-table-column prop="objeto" label="descripcion"></el-table-column>
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
                            <el-table-column prop="objeto" label="descripcion"></el-table-column>
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
                    <div class="grid-content bg-purple">
                        <p>informacion adicional que debe brindar para realizar su memorial rapidamente</p>
                    </div>
                    <br>
                    <div style="text-align: right; float: right">
                        <el-button type="primary" size="small" @click="setValuesAcquired()">guardar la solicitudes
                            seleccionadas
                        </el-button>
                    </div>
                </el-col>
            </el-row>
        </el-card>
    </div>
</template>
  
<script>
import { allowedNodeEnvironmentFlags } from 'process';

export default {
    name: "",
    data() {
        return {
            client: this.$store.state.user,
            loading: true,
            dataMemorials: [],
            acquired: [],
            checkboxGroup1: [],
        };
    },
    mounted() {
        this.getTypesOfMemorials();
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
                console.log(app.dataMemorials);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        //  *  T2. Guardar los valores para la venta en linea
        async setValuesAcquired() {
            var app = this;
            console.log(app.acquired);
            try {
                if (app.acquired.length <= 0) {
                    this.$alert('DEBE SELECCIONAR POR LO MENOS UN VALOR PARA CREAR LA SOLICITUD', 'HA OCURRIDO UN ERROR', {
                        confirmButtonText: 'BUENO',
                    });
                } else {
                    let response = await axios.post("/api/setValuesAcquired/", {
                        client: app.client,
                        acquired: app.acquired,
                        marker: "SALE",
                    });
                    this.$alert('ACABA DE CREAR UNA NUEVA SOLICITUD, SI CUENTA CON EL COMPROBANTE DE PAGO PUEDE REALIZAR SU REGISTRO', 'LO HA CONSEGUIDO', {
                        confirmButtonText: 'BUENO',
                    });
                    console.log(response.data[0].ff_nueva_solicitud);
                    let id = response.data[0].ff_nueva_solicitud;
                    this.$router.push({
                        name: "boucherofrequest",
                        params: {
                            id: id,
                        },
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
            this.acquired.push(row);
        },
        // * FUNLOCAL. Quitar valores que se iban a comprar
        initRemoveValues(index, row) {
            this.acquired.splice(index, 1);
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
</style>
  