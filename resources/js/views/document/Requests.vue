<template>
    <div>
        <el-card style="margin-bottom: 50px;">
            <div class="header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title" style="margin: 10; font-weight: bold;">
                    Compra de Valores en Línea
                </h3>
                <el-button type="primary" size="medium" icon="el-icon-plus" @click="initAddRequestInLine"
                    style="background-color: #ff5722; border-color: #ff5722; color: white; font-weight: bold;">
                    Realice su Solicitud de Compra de Valores
                </el-button>
            </div>
            <!-- Mensajes -->
            <div class="alerts-container">
                <el-alert title="Seleccione el boton de color naranja si desea comprar valores en linea." type="error"
                    show-icon class="alert-space" />
                <el-alert
                    title="Mientras no se verifique su pago (tarda entre 5 a 30 minutos ya que el proceso es automático), el estado de su solicitud estará en proceso. Si cambia el estado a procesado puede imprimir su comprobante de pago en: imprimir comprobante."
                    type="success" show-icon />
            </div>

            <!-- Tabla para dispositivos grandes -->
            <el-table v-if="!isSmallDevice" :data="requests" border style="width: 100%; margin-top: 15px">
                <el-table-column label="numero">
                    <template slot-scope="scope">
                        <div slot="reference" class="name-wrapper">
                            <el-tag size="medium">{{ scope.row.idc }}</el-tag>
                        </div>
                    </template>
                </el-table-column>
                <!--
                <el-table-column prop="numero" label="Número" />
                    -->
                <el-table-column prop="fecha" label="Fecha" />
                <el-table-column prop="importe" label="Importe" />
                <el-table-column prop="estado" label="Estado">
                    <template slot-scope="scope">
                        <el-tag :type="tagType(scope.row.estado)" effect="dark">{{ scope.row.estado }}</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="detalle" label="Detalle" />
                <el-table-column label="Acciones">
                    <template slot-scope="scope">
                        <el-button type="primary" size="mini" @click="initPrintRequestReport(scope.$index, scope.row)">Imprimir Comprobante</el-button>
                    </template>
                </el-table-column>
            </el-table>

            <!-- Diseño responsivo para dispositivos pequeños -->
            <div v-else class="responsive-container">
                <div v-for="(row, index) in requests" :key="index" class="responsive-row">
                    <div class="responsive-item">
                        <div class="item-title">Número</div>
                        <div class="item-content">
                            <el-tag>{{ row.idc }}</el-tag>
                        </div>
                    </div>
                    <div class="responsive-item">
                        <div class="item-title">Fecha</div>
                        <div class="item-content">{{ row.fecha }}</div>
                    </div>
                    <div class="responsive-item">
                        <div class="item-title">Importe</div>
                        <div class="item-content">{{ row.importe }}</div>
                    </div>
                    <div class="responsive-item">
                        <div class="item-title">Estado</div>
                        <div class="item-content">
                            <el-tag :type="tagType(row.estado)" effect="dark">{{ row.estado }}</el-tag>
                        </div>
                    </div>
                    <div class="responsive-item" v-if="row.detalle">
                        <div class="item-title">Detalle</div>
                        <div class="item-content">{{ row.detalle }}</div>
                    </div>
                    <div class="responsive-item">
                        <div class="item-title">Acciones</div>
                        <div class="item-content">
                            <el-button type="primary" size="mini" @click="initPrintRequestReport(row.index, row)">Imprimir Comprobante</el-button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paginación -->
            <el-pagination background layout="prev, pager, next" :page-size="pagination.per_page"
                :current-page="pagination.current_page" :total="pagination.total" @current-change="getRequests"
                class="pagination" />
        </el-card>
    </div>
</template>

<script>
export default {
    name: "lista_de_solicitudes_para_la_venta_en_linea",
    data() {
        return {
            loading: true,
            user: this.$store.state.user,
            requests: [],
            pagination: {
                page: 1,
            },
            isSmallDevice: window.innerWidth <= 768,
        };
    },

    mounted() {
        let app = this;
        this.getRequests(app.pagination.page);
        window.addEventListener("resize", this.updateDeviceSize);
    },
    destroyed() {
        window.removeEventListener("resize", this.updateDeviceSize);
    },
    /*
    computed: {
        isSmallDevice() {
            return window.innerWidth <= 768;
        },
    },*/
    methods: {
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
        test() {
            alert("en proceso de desarrollo");
        },
        async getRequests(page) {
            let app = this;
            try {
                let response = await axios.post("/api/request", {
                    client: app.user,
                    year: app.user.gestion,
                    page: page,
                });
                app.loading = false;
                app.requests = Object.values(response.data.data);
                app.pagination = response.data;
                console.log(response.data.data);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        //  *  Route. Iniciar una nueva solicitud para la venta en linea de valores
        initAddRequestInLine() {
            this.$router.push({
                name: "salestudents",
            });
        },
        initPrintRequestReport(idx, row) {
            let id = row.id;
            this.$router.push({
                name: "salevaluesdetails",
                params: {
                    id: id,
                },
            });
        },
    },
};
</script>

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