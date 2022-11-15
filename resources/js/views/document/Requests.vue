<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>solicitudes</span>
            </div>
            <!--
            <div style="margin-top: 15px;">
                <el-input placeholder="INSERTE UNA DESCRIPCION" v-model="writtenTextParameter"
                    class="input-with-select">
                    <el-button slot="append" icon="el-icon-search" @click="initSearchPerson"></el-button>
                </el-input>
            </div>
            -->
            <br />
            <div>
                <el-table v-loading="loading" :data="requests" style="width: 100%">
                    <el-table-column prop="tipo" label="tipo"></el-table-column>
                    <el-table-column prop="idc" label="id"></el-table-column>
                    <el-table-column prop="ci_per" label="ci"></el-table-column>
                    <el-table-column prop="des_per" label="descripcion" width="280"></el-table-column>
                    <el-table-column align="right" width="220">
                        <template slot-scope="scope">
                            <el-button @click="initSaleBoucher(scope.$index, scope.row)" type="primary" size="mini"
                                plain>Depositar</el-button>
                            <el-button @click="initEditRequest(scope.$index, scope.row)" type="danger" plain
                                size="mini">
                                Editar</el-button>
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
    name: "Personas",
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
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
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
        initEditRequest(idx, row) { },
    },
};
</script>
  
<style scoped>
.el-input .el-select {
    width: 180px;
}
</style>
  