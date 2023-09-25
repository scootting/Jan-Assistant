<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>lista de solicitudes de solvencia universitaria</span>
                <el-button size="small" type="default" icon="el-icon-plus" @click="initAddRequestSolvency"
                    style="text-align: right; float: right">
                    nueva solicitud de solvencia universitaria</el-button>
            </div>
            <p>
                <el-alert title="importante" type="error"
                    description="las solicitudes tienen validez de 30 dias calendario a partir de la fecha solicitada, durante ese periodo debe realizar la cancelacion del importe, a traves de caja universitaria y seguir con el procedimiento que se indica en la solicitud"
                    show-icon>
                </el-alert>
            </p>
            <br />
            <div>
                <el-table v-loading="loading" :data="dataSolvencies" style="width: 100%">
                    <el-table-column label="Fecha" width="120">
                        <template slot-scope="scope">
                            <i class="el-icon-time"></i>
                            <span style="margin-left: 10px">{{ scope.row.fec_tra }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="numero de solicitud" width="150" align="center">
                        <template slot-scope="scope">
                            <div slot="reference" class="name-wrapper">
                                <el-tag size="medium" effect="dark">{{ scope.row.idc }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="tipo de solvencia" width="350">
                        <template slot-scope="scope">
                            <span>solvencia para {{ scope.row.des_tipo }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="estado" width="150" align="center">
                        <template slot-scope="scope">
                            <div slot="estado" class="name-wrapper">
                                <el-tag size="medium" effect="dark" type="danger">{{ scope.row.estado }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column align="right" width="250">
                        <template slot-scope="scope">
                            <el-button @click="initReportRequestMemorial(scope.$index, scope.row)" type="warning" plain
                                size="mini">
                                imprimir la solicitud</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
                    :current-page="pagination.current_page" :total="pagination.total"
                    @current-change="getDataSolvencies">
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
            dataSolvencies: [],
            pagination: {
                page: 1,
            },
        };
    },
    mounted() {
        let app = this;
        this.getDataSolvencies(app.pagination.page);
    },
    methods: {
        test() {
            alert("en proceso de desarrollo");
        },

        //  * M2. Lista las solicitudes de elaboracion de memorial universitario              
        async getDataSolvencies(page) {
            let app = this;
            console.log(app.user);
            let typea = 'SOL';
            try {
                let response = await axios.post("/api/getDataDocument", {
                    client: app.user,
                    page: page,
                    typea: typea,
                });
                app.loading = false;
                app.dataSolvencies = Object.values(response.data.data);
                app.pagination = response.data;
                console.log(response.data.data);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
                app.loading = false;
            }
        },
        //  * M3. Imprimir la solicitud de elaboracion de solvencia universitaria            
        initReportRequestMemorial(idx, row) {
            let app = this;
            console.log(app.dataSaleDay);
            axios({
                url: "/api/reportRequestMemorial/",
                params: {
                    id: row.id,
                    gestion: app.user.gestion,
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
        //  *  Route. Iniciar una nueva solicitud para iniciar con el proceso de solvencia universitaria
        initAddRequestSolvency() {
            this.$router.push({
                name: "addrequestsolvencies",
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
  