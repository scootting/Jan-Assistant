<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Lista de mesas habilitadas</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row :gutter="20">
                <el-col :span="24">
                    <p>
                        <el-alert title="Nota importante"
                            description="El resultado de esta consulta es de la lista de mesas habilitadas emitida por el comite electoral para las elecciones a vicerrector."
                            type="success" show-icon>
                        </el-alert>
                    </p>
                    <p>
                        <el-button type="primary" size="small" @click="initRequestInformation">volver a
                            consultar</el-button>
                    </p>
                </el-col>
                <el-col :span="24">
                    <div class="grid-content bg-purple">
                        <el-row>
                            <el-col :span="4" :xs="22" :offset="1" v-for="(item, index) in dataTablets" :key="index">
                                <div style="align-items: center;">
                                    <el-card :body-style="{ height: '180px'}" style="margin-top: 10px;">
                                        <div slot="header" class="clearfix">
                                            <span>MESA NO. {{ item.numero }}</span>
                                            <span style="float: right; padding: 3px 0">{{ item.sede }}</span>
                                            <!--
                                            <el-button  type="text"
                                                @click="initReportTabletDetail(item)">Como puedo llegar?</el-button>

                                            -->
                                        </div>
                                        <h1>{{ item.ubicacion }}</h1>
                                        <p>{{ item.obervaciones }}</p>
                                        <p>{{ item.descripcion }}</p>
                                        <div v-if="item.estado !== 'Habilitado'">
                                            <el-button type="danger" size="small"
                                                @click="initReportVotesForTablet(item)">Ver resultados</el-button>
                                        </div>
                                        <div v-else>
                                            <el-button type="danger" size="small" disabled
                                                @click="initReportVotesForTablet(item)">Ver resultados</el-button>
                                        </div>
                                    </el-card>
                                </div>
                            </el-col>
                        </el-row>
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
            loading: true,
            dataTablets: [],
            id_election: 0,
        };
    },
    mounted() {
        this.id_election = this.$route.params.id;
        this.getInformationTablets();
    },
    methods: {
        test() {
            alert("Hola, como estas hoy?");
        },

        async getInformationTablets() {
            var app = this;
            try {
                let response = await axios.post("/getInformationTablets", {
                    id_election: app.id_election
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
            alert("Hola, como estas hoy?");
        },
        initReportVotesForTablet(row) {
            let app = this;
            console.log(app.id);
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
  