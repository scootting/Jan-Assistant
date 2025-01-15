<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>lista de solicitudes para la venta de valores en linea</span>
                <el-button size="small" type="primary" icon="el-icon-plus" @click="initAddRequestInLine"
                    style="text-align: right; float: right">
                    nueva solicitud para la venta de valores en linea</el-button>
            </div>
            <br />
            <div>
                <el-alert title="Que debo hacer?" type="error"
                    description="Seleccione la opcion de nueva solicitud para la compra de los valores que desea adquirir.">
                </el-alert>
                <br>
                <el-alert title="Como se que ya se proceso mi solicitud?" type="success"
                    description="Mientras no realice el pago o no se verifique su pago (tarda entre 5 a 30 minutos ya que el proceso es automatico) el estado de su solicitud estara en proceso, si cambia el estado a procesado puede imprimir su comprobante de pago en ver detalles de la solicitud.">
                </el-alert>
                <br>
                <el-table v-loading="loading" :data="requests" style="width: 100%">
                    <el-table-column label="numero" width="150">
                        <template slot-scope="scope">
                            <div slot="reference" class="name-wrapper">
                                <el-tag size="medium" effect="dark">{{ scope.row.idc }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="fecha" label="fecha" width="150"></el-table-column>
                    <el-table-column prop="importe" label="importe" width="100" align="right"></el-table-column>
                    <el-table-column prop="estado" label="estado" width="150" align="center">
                        <template slot-scope="scope">
                            <div v-if="scope.row.estado === 'PROCESADO'">
                                <div slot="reference" class="name-wrapper">
                                    <el-tag size="medium" effect="dark" type="success">{{ scope.row.estado }}</el-tag>
                                </div>
                            </div>
                            <div v-if="scope.row.estado === 'CREADO'">
                                <div slot="reference" class="name-wrapper">
                                    <el-tag size="medium">{{ scope.row.estado
                                        }}</el-tag>
                                </div>
                            </div>
                            <div v-if="scope.row.estado === 'EXPIRADO'">
                                <div slot="reference" class="name-wrapper">
                                    <el-tag size="medium" effect="dark" type="warning">{{ scope.row.estado
                                        }}</el-tag>
                                </div>
                            </div>
                            <div v-if="scope.row.estado === 'EN PROCESO'">
                                <div slot="reference" class="name-wrapper">
                                    <el-tag size="medium" effect="dark" type="primary">{{ scope.row.estado
                                        }}</el-tag>
                                </div>
                            </div>
                            <div v-if="scope.row.estado === 'FALLIDO'">
                                <div slot="reference" class="name-wrapper">
                                    <el-tag size="medium" effect="dark" type="danger">{{ scope.row.estado
                                        }}</el-tag>
                                </div>
                            </div>
                            <div v-if="scope.row.estado === 'ANULADO'">
                                <div slot="reference" class="name-wrapper">
                                    <el-tag size="medium" effect="dark" type="danger">{{ scope.row.estado
                                        }}</el-tag>
                                </div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="detalle" align="right">
                        <p>por la venta de valores universitarios</p>
                    </el-table-column>
                    <el-table-column align="right" fixed="right">
                        <template slot-scope="scope">
                            <el-button @click="initPrintRequestReport(scope.$index, scope.row)" type="primary"
                                size="small">
                                imprimir comprobante</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
                    :current-page="pagination.current_page" :total="pagination.total" @current-change="getRequests">
                </el-pagination>
            </div>
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
        };
    },
    mounted() {
        let app = this;
        this.getRequests(app.pagination.page);

    },
    methods: {
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
        //  *  Route. Iniciar el registro de comprobantesde pago para la venta en linea de valores
        /*
        initSaleBoucher(idx, row) {
            console.log(idx, row);
            let id = row.id;
            this.$router.push({
                name: "boucherofrequest",
                params: {
                    id: id,
                },
            });
        },
        */
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
.el-input .el-select {
    width: 180px;
}
</style>