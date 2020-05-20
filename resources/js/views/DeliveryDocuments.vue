<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Documentos de compra</span>
                <el-button style="float: right; padding: 3px 0" type="text"
                    >nuevo documento de compra</el-button
                >
            </div>
            <div>
                <el-search-table-pagination
                    type="local"
                    :data="list"
                    :page-sizes="[5, 10, 15]"
                    :columns="columns"
                    :form-options="formOptions"
                >
                    <template slot="nro_doc" slot-scope="scope">
                        <el-button @click="redirectVUE(scope.row)">{{
                            scope.row.nro_doc
                        }}</el-button>
                    </template>
                </el-search-table-pagination>
            </div>
        </el-card>
    </div>
</template>

<script>
export default {
    name: "DocumentoDeCompra",
    data() {
        return {
            user: this.$store.state.user,
            messages: {},
            data: {},
            list: [],
            
            columns: [
                {
                    prop: "nro_doc",
                    label: "Nro_doc",
                    width: 140,
                    slotName: "nro_doc"
                },
                { prop: "fecha_doc", label: "Fecha", width: 140 },
                { prop: "responsable", label: "Resposable", width: 180 },
                { prop: "ofc_des", label: "Oficina", width: 180 }
            ]
        };
    },
    created() {
        var app = this;
        axios
            .get("/api/DeliveryDocuments")
            .then(response => {
                console.log(response);
                app.list = response.data;
            })
            .catch(error => {
                this.error = error;
                this.$notify.error({
                    title: "Error",
                    message: this.error.message
                });
            });
    },
    mounted() {},
    methods: {
        test() {
            alert("bienvenido al modulo");
        },
        redirectVUE(scope) {
            console.log(scope);
            this.$store.state.encargado = scope;
            this.$router.push({ name: "editdeliverydocument" });
        }
    }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>