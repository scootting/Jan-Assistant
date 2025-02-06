<template>
    <div>
        <el-card style="margin-bottom: 50px;">
            <div class="header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title" style="margin: 10; font-weight: bold;">
                    datos del comprobante de pago
                </h3>
                <el-button type="primary" size="small" @click="test">
                    ayuda
                </el-button>
            </div>
            <!-- Mensajes -->
            <el-alert title="Cuanto tiempo dura verificar su pago?" type="error" show-icon class="alert-space"
                description="Despues de realizado el pago, toma de 10 a 30 minutos verificarlo ya que este proceso es automatico, en cuanto se haga efectivo, el estado de la solicitud cambiara a procesado, y se le habilitara la opcion para imprimir su comprobante de pago">
            </el-alert>
            <br>
            <el-alert title="Como saber si todo esta correcto?" type="success" show-icon class="alert-space"
                description="El comprobante de pago impreso cuenta con un codigo Qr Unico, al escanearlo le redireccionara a nuestro servicio de verificacion, se le recomienda no compartir esta informacion ya que la falsificacion de este documento esta castigado de acuerdo a normas internas.">
            </el-alert>

            <!-- Tabla para dispositivos grandes -->
            <h2 v-if="!isSmallDevice">
                <p>datos de la solicitud</p>
            </h2>
            <el-form ref="form" :model="this.dataRequest" label-width="200px" size="mini" v-if="!isSmallDevice">
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
                    <el-tag :type="tagType(dataRequest.estado)" effect="dark">{{ dataRequest.estado }}</el-tag>
                </el-form-item>
            </el-form>
            <h2 v-if="!isSmallDevice">
                <p>valores adquiridos</p>
            </h2>
            <el-table v-if="!isSmallDevice" :data="dataRequestDetails" border style="width: 100%; margin-top: 15px"
                show-summary sum-text="importe total">
                <el-table-column prop="des_val" label="descripcion" width="950"
                    style="text-align: center !important;"></el-table-column>
                <el-table-column prop="can_val" label="cantidad" width="90" align="right"></el-table-column>
                <el-table-column prop="imp_val" label="precio" width="90" align="right"></el-table-column>
                <el-table-column align="right" width="200" fixed="right">
                    <template slot-scope="scope" v-if="scope.row.cod_val !== '9999'">
                        <el-button @click="initPrintComprobate(scope.$index, scope.row)" type="primary" size="mini"
                            :disabled="scope.row.id_tran === 0">imprimir comprobante
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>

            <!-- Diseño responsivo para dispositivos pequeños -->
            <div v-else class="responsive-container">
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
                        <el-tag effect="dark" :type="tagType(dataRequest.estado)">{{ dataRequest.estado }}</el-tag>
                    </el-form-item>
                </el-form>
                <h4>valores adquiridos</h4>
                <div v-for="(row, index) in dataRequestDetails" :key="index" class="responsive-row">
                    <div class="responsive-item">
                        <div class="item-title">Descripcion</div>
                        <div class="item-content">{{ row.des_val }}</div>
                    </div>
                    <div class="responsive-item">
                        <div class="item-title">cantidad</div>
                        <div class="item-content">{{ row.can_val }}</div>
                    </div>
                    <div class="responsive-item">
                        <div class="item-title">Importe</div>
                        <div class="item-content">{{ row.imp_val }}</div>
                    </div>
                    <!--
                    -->
                    <div class="responsive-item">
                        <div class="item-title">Acciones</div>
                        <div class="item-content" v-if="row.cod_val !== '9999'">
                            <el-button @click="initPrintComprobate(index, row)" type="primary" size="mini"
                                :disabled="row.id_tran === 0">imprimir comprobante
                            </el-button>
                        </div>
                    </div>
                </div>
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
            id: this.$route.params.id,
            loading: true,
            offered: [],
            acquired: [],
            total: 1.00,
            dataRequest: {},
            dataRequestDetails: [],
            isSmallDevice: window.innerWidth <= 768,
        };
    },
    mounted() {
        this.getDataRequestById();
        window.addEventListener("resize", this.updateDeviceSize);
    },
    destroyed() {
        window.removeEventListener("resize", this.updateDeviceSize);
    },
    methods: {
        test() {
            alert("llamar al 74246032 para asistencia tecnica");
        },
        updateDeviceSize() {
            this.isSmallDevice = window.innerWidth <= 768;
        },
        tagType(estado) {
            if (estado === "PROCESADO") return "success";
            if (estado === "EN PROCESO") return "primary";
            if (estado === "CREADO") return "info";
            if (estado === "EXPIRADO") return "info";
            if (estado === "FALLIDO") return "danger";
            if (estado === "ANULADO") return "danger";
            return "warning";
        },
        responsiveRowClass({ rowIndex }) {
            return rowIndex % 2 === 0 ? "row-even" : "row-odd";
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
.alert-space {
    margin-bottom: 15px;
}

.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.add-request-btn {
    align-self: center;
}

.alerts-container {
    margin-bottom: 20px;
}

.responsive-container {
    display: flex;
    flex-direction: column;
    gap: 15px;
    /* Espacio entre las filas */
}

.responsive-row {
    display: flex;
    flex-direction: column;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: white;
    gap: 10px;
    /* Espacio entre los elementos */
}

.responsive-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}

.item-title {
    font-weight: bold;
    color: #555;
    flex: 1;
}

.item-content {
    flex: 2;
}

.pagination {
    margin-top: 15px;
    text-align: right;
}

@media (max-width: 768px) {
    .header-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .responsive-row {
        margin-bottom: 10px;
    }

    .responsive-item {
        flex-direction: row;
        gap: 10px;
    }

    .alerts-container {
        margin-bottom: 30px;
        /* Espacio extra para dispositivos pequeños */
    }

    .header {
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .card-title {
        margin-bottom: 10px;
    }
}
</style>