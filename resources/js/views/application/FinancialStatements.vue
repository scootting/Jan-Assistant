<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>estados financieros</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row :gutter="10">
                <el-col :span="18">
                    <div class="grid-content bg-purple">
                        <p>Elija la gestion que desea consultar</p>
                        <el-form label-width="100px">
                            <el-form-item label="gestion">
                                <el-select v-model="gestion" value-key="id" size="small"
                                    placeholder="seleccione el tipo de documento" @change="OnchangeTypeDocument">
                                    <el-option v-for="item in dataFinancial" :key="item.id" :label="item.gestion"
                                        :value="item.id">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-form>
                        <p>
                            <!--
                            <h4>documentos de la gestion: {{ gestion }}</h4>
                    <el-alert
                        title="Esta informacion es generada por el Sistema de Gestion Publica (SIGEP) para la Universidad Autonoma Tomas Frias."
                        type="success">
                    </el-alert>

                    -->
                        </p>
                        <div>
                            <el-table :data="dataDocuments" border style="width: 100%" size="small">
                                <el-table-column prop="idx" label="indice" align="right" width="100">
                                </el-table-column>
                                <el-table-column prop="descripcion" label="descripcion" width="450" align="left">
                                    <template slot-scope="scope">
                                        <el-tag size="medium">{{ scope.row.descripcion }}</el-tag>
                                    </template>
                                </el-table-column>
                                ...
                                <el-table-column align="right-center" label="" width="150" fixed="right">
                                    <template slot-scope="scope">
                                        <el-button :disabled="scope.row.guardado === true" type="primary" size="small"
                                            @click="getDigitalDocumentById(scope.$index, scope.row)">ver
                                            documento</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </div>
                </el-col>
            </el-row>
        </el-card>
    </div>
</template>

<script>
export default {
    name: "",
    data() {
        return {
            client: this.$store.state.user,
            id: '',
            gestion: '',
            dataFinancial: [],          //diferentes tipos de archivos que pertenecen a un documento
            dataDocuments: [],
        };
    },
    mounted() {
        this.getFinancialStatements();
    },
    methods: {
        test() {
            alert("bienvenido al modulo");

        },

        //  * EF1. Obtener la lista de estados financieros 
        async getFinancialStatements() {
            let app = this;
            try {
                let response = await axios.post("/getFinancialStatements", {
                    id: app.id,
                    year: app.client.gestion,
                });
                app.dataFinancial = response.data;
                //console.log(response);
            } catch (error) {

                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
                console.log(error);
            }
        },

        //* actualizar un componente al hacer la seleccion nueva *//
        OnchangeTypeDocument(idx) {
            console.log(idx);
            let resultado = this.dataFinancial.find(data => data.id == idx);
            this.id = resultado.id;
            this.gestion = resultado.gestion;
            this.getDocumentsbyFinalcialStatemet();
        },
        //  * A2. Obtiene la lista de documentos que pertenecen a un archivo
        async getDocumentsbyFinalcialStatemet() {
            let app = this;
            try {
                let response = await axios.post("/getDocumentsbyFinalcialStatemet", {
                    id: app.id,
                    year: app.gestion,
                });
                app.dataDocuments = response.data;
                console.log(response.data);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        //  * EF4. Obtener documentos digitalizados
        getDigitalDocumentById(idx, row) {
            let app = this;
            axios({
                url: "/api/getDigitalFinancialDocument",
                params: {
                    id: row.id,
                    year: app.gestion,
                },
                method: "GET",
                responseType: "blob",
            }).then((response) => {
                let pdfData = response.data;
                console.log(response);
                let blob = new Blob([pdfData], { type: 'application/pdf' });
                let url = URL.createObjectURL(blob);
                window.open(url);
            });
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