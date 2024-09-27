<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>venta de valores en linea</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row :gutter="20">
                <p>
                    <el-alert title="Cuanto tiempo dura verificar su pago?" type="error"
                        description="Despues de realizado el pago, toma de 10 a 30 minutos verificarlo ya que este proceso es automatico, en cuanto se haga efectivo, el estado de la solicitud cambiara a procesado, y se le habilitara la opcion para imprimir su comprobante de pago">
                    </el-alert>
                    <br>
                    <el-alert title="Como saber si todo esta correcto?" type="success"
                        description="El comprobante de pago impreso cuenta con un codigo Qr Unico, al escanearlo le redireccionara a nuestro servicio de verificacion, se le recomienda no compartir esta informacion ya que la falsificacion de este documento esta castigado de acuerdo a normas internas.">
                    </el-alert>
                </p>
                <el-col :span="9">
                    <div class="grid-content bg-purple">
                        <p>datos de la solicitud</p>
                        <el-form ref="form" :model="this.dataRequest" label-width="200px" size="mini">
                            <el-form-item label="numero de solicitud">
                                {{ dataRequest.idc }}
                            </el-form-item>
                            <el-form-item label="fecha en la que se realizo">
                                {{ dataRequest.fecha }}
                            </el-form-item>
                            <el-form-item label="apellidos y nombres">
                                {{ dataRequest.des_per }}
                            </el-form-item>
                            <el-form-item label="importe">
                                {{ dataRequest.importe }}
                            </el-form-item>
                            <el-form-item label="estado">
                                <el-tag type="" effect="dark">{{ dataRequest.estado }}</el-tag>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-col>
                <el-col :span="15">
                    <div class="grid-content bg-purple">
                        <p>valores en linea adquiridos</p>
                        <el-table :data="dataRequestDetails" style="width: 100%" show-summary sum-text="importe total">
                            <el-table-column prop="des_val" label="descripcion" width="300"></el-table-column>
                            <el-table-column prop="can_val" label="cantidad" width="90" align="right"></el-table-column>
                            <el-table-column prop="imp_val" label="precio" width="90" align="right"></el-table-column>
                            <el-table-column align="right" width="200" fixed="right">
                                <template slot-scope="scope" v-if="scope.row.id_compuesto === 'U'">
                                    <el-button @click="initPrintComprobate(scope.$index, scope.row)" type="primary"
                                        size="mini" :disabled="scope.row.id_tran === 0">imprimir comprobante
                                    </el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                    <br>
                    <div style="text-align: right; float: right">
                        <!--
                        <el-button type="primary" size="small" @click="setValuesAcquired()">guardar la solicitud de valores
                            en linea
                        </el-button>
                        -->
                    </div>
                </el-col>
            </el-row>
            <el-row> </el-row>
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
            id: this.$route.params.id,
            loading: true,
            offered: [],
            acquired: [],
            total: 1.00,
            dataRequest: {},
            dataRequestDetails: [],
        };
    },
    mounted() {
        this.getDataRequestById();
    },
    methods: {
        test() {
            alert("llamar al 74246032 para asistencia tecnica");
        },
        //  *  D3. Obtener la informacion por cada solicitud
        //  * {id: id de la solicitud }
        async getDataRequestById() {
            var app = this;
            try {
                let response = await axios.post("/api/getDataRequestById", {
                    id: app.id,
                });
                app.loading = false;
                console.log(response);
                app.dataRequest = response.data.dataRequest[0];
                app.dataRequestDetails = response.data.dataRequestDetails;
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        async initPrintComprobate(idx, row) {
            console.log(idx);
            console.log(row);
            let app = this;
            axios({
                url: "/api/printComprobate",
                params: {
                    id: row.id_tran,
                    cod: row.cod_val,
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
  