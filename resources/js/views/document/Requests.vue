<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>lista de solicitudes para la venta de valores en linea</span>
                <el-button size="small" type="default" icon="el-icon-plus" @click="initAddRequestInLine"
                    style="text-align: right; float: right">
                    nueva solicitud para la venta de valores en linea</el-button>
            </div>
            <el-alert title="estados de la solicitud" type="success"
                description="creado: cuando se tiene los valores seleccionados, pero no se creo el CPT, en proceso: falta pagar por el CPT generado, procesado: cuando se cancelo el pago total, anulado: cuando se anulo la solicitud de CPT, expirado: cuando paso el tiempo valido para pagar por el CPT"
                show-icon>
            </el-alert>
            <br />
            <div>
                <el-table v-loading="loading" :data="requests" style="width: 100%">
                    <el-table-column prop="fecha" label="fecha" width="150"></el-table-column>
                    <el-table-column label="numero" width="150">
                        <template slot-scope="scope">
                            <div slot="reference" class="name-wrapper">
                                <el-tag size="medium" effect="dark">{{ scope.row.idc }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="importe" label="importe" width="150" align="right"></el-table-column>
                    <el-table-column prop="estado" label="estado" width="150" align="center">
                        <template slot-scope="scope">
                            <div slot="reference" class="name-wrapper">
                                <el-tag size="medium" effect="dark" type="danger">{{ scope.row.estado }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="detalle" width="450" align="right"><p>por la venta de valores universitarios</p></el-table-column>
                    <el-table-column align="right" width="220">
                        <template slot-scope="scope" v-if="scope.row.estado === 'PROCESADO'">
                            <!--
                            <el-button @click="initSaleBoucher(scope.$index, scope.row)" type="primary" size="mini"
                                plain>registrar deposito del comprobante de pago</el-button>
                            -->
                            
                            <el-button @click="initPrintRequestReport(scope.$index, scope.row)" type="success" plain size="small">
                                imprimir comprobantes de pago</el-button>
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
  