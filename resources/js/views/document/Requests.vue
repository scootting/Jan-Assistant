<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>solicitudes</span>
            </div>
            <div style="margin-top: 15px;">
                <el-input placeholder="INSERTE UNA DESCRIPCION" v-model="writtenTextParameter"
                    class="input-with-select">
                    <el-button slot="append" icon="el-icon-search" @click="initSearchPerson"></el-button>
                </el-input>
            </div>
            <br />
            <div>
                <el-table v-loading="loading" :data="requests" style="width: 100%">
                    <el-table-column prop="personal" label="CARNET"></el-table-column>
                    <el-table-column prop="paterno" label="PATERNO"></el-table-column>
                    <el-table-column prop="materno" label="MATERNO"></el-table-column>
                    <el-table-column prop="nombres" label="NOMBRES" width="280"></el-table-column>
                    <el-table-column align="right" width="220">
                        <template slot-scope="scope">
                            <el-button @click="initEditBoucher(scope.$index, scope.row)" type="primary" size="mini"
                                plain>Depositar</el-button>
                            <el-button @click="initShowRequest(scope.$index, scope.row)" type="danger" plain
                                size="mini">
                                Mostrar</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
                    :current-page="pagination.current_page" :total="pagination.total"
                    @current-change="getDataPageSelected"></el-pagination>
            </div>
        </el-card>
    </div>
</template>
  
<script>
export default {
    name: "Personas",
    data() {
        return {
            requests: [],
            pagination: {
                page: 1,
            },
            writtenTextParameter: "",
            loading: true,
        };
    },
    mounted() {
        let app = this;
        this.getRequest(app.pagination.page);
        axios
            .post("/api/persons", {
                descripcion: app.writtenTextParameter,
            })
            .then((response) => {
                app.loading = false;
                app.people = response.data.data;
                app.pagination = response.data;
            })
            .catch((error) => {
                this.error = error;
                this.$notify.error({
                    title: "Error",
                    message: this.error.message,
                });
            });
    },
    methods: {
        test() {
            alert("en proceso de desarrollo");
        },
        getRequest(page) {
        },

        /*
        initAddPerson() {
            this.$router.push({
                name: "addperson",
            });
        },
        initEditPerson(index, row) {
            console.log(index, row);
            let personal = row.personal;
            this.$router.push({
                name: "editperson",
                params: {
                    id: personal.trim(),
                },
            });
        },
        initShowPerson(index, row) {
            let personal = row.nro_dip;
            //router.push({ name: 'editperson', params: { userId: personal }})
        },*/
    },
};
</script>
  
<style scoped>
.el-input .el-select {
    width: 180px;
}
</style>
  