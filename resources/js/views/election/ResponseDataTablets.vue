<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Lista de mesas habilitadas</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row :gutter="20">
                <p>
                    <el-alert title="Nota importante"
                        description="El resultado de esta consulta es de la lista de mesas habilitadas emitida por el comite electoral para las elecciones a vicerrector."
                        type="success" show-icon>
                    </el-alert>
                </p>
                <el-col :span="24">
                    <div class="grid-content bg-purple">
                        <el-row>
                            <el-col :span="4" offset="1" v-for="(item, index) in dataTablets" :key="index">
                                <div style="align-items: center;">
                                    <el-card>
                                        <el-button type="text" icon="el-icon-s-claim" circle
                                            @click.native="initReportTabletDetail(item)"></el-button>
                                        <h4>MESA NO.{{ item.numero }}</h4>
                                        <h1>{{ item.ubicacion }}</h1>
                                        <p>{{ item.obervaciones }}</p>
                                        <p>{{ item.descripcion }}</p>
                                    </el-card>
                                    <p>
                                        <el-button type="danger" @click="test">Como puedo llegar?</el-button>
                                    </p>
                                </div>
                            </el-col>
                        </el-row>
                    </div>
                </el-col>
                <el-col>
                    <el-button type="primary" size="small" @click="initRequestInformation">volver a consultar</el-button>
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
            loading: true,
            dataTablets: [],
        };
    },
    mounted() {
        this.getInformationTablets();
    },
    methods: {
        test() {
            alert("Hola, como estas hoy?");
        },

        async getInformationTablets() {
            var app = this;
            try {
                let response = await axios.post("/getInformationTablets/", {
                    id_election: '2'
                });
                app.loading = false;
                app.dataTablets = response.data.dataTablets;
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        initReportTabletDetail(row) {
            let app = this;
        },
        initRequestInformation() {
            this.$router.push({
                name: "informationelection",
            });
        },

    },
};
</script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.bg-purple .el-card .el-button {
    font-size: 5rem;
    display: block;
    margin: 0 auto;
}

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
  