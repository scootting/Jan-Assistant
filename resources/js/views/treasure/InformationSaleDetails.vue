<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>detalle de la transaccion</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row :gutter="20">
                <p>
                    <el-alert title="importante" type="error"
                        description="Esta informacion es generada de las ventas realizadas por la universidad a traves de la Division de Tesoro"
                        show-icon>
                    </el-alert>
                </p>
                <el-col :span="18" :offset="3">
                    <div class="grid-content bg-purple">
                        <p>datos de la venta realizada</p>
                        <el-form ref="form" :model="this.dataTransaction" label-width="200px" size="mini">
                            <el-form-item label="fecha">
                                {{ dataTransaction.fec_tra }}
                            </el-form-item>
                            <el-form-item label="valor universitario">
                                {{ dataTransaction.cod_val  + " - " + dataTransaction.des_val}}
                            </el-form-item>
                            <el-form-item label="apellidos y nombres">
                                {{ dataTransaction.des_per }}
                            </el-form-item>
                            <el-form-item label="importe">
                                {{ dataTransaction.imp_val }}
                            </el-form-item>
                            <el-form-item label="estado">
                                <el-tag type="" effect="dark">{{ 'verificado' }}</el-tag>
                            </el-form-item>
                        </el-form>
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
            id: this.$route.params.id,
            dataTransaction: {},
        };
    },
    mounted() {
        this.getDataTransactionById();
    },
    methods: {
        test() {
            alert("llamar al 74246032 para asistencia tecnica");
        },
        //  *  T3. obtiene la informacion del comprobante de pago
        //  * {id: id de la transaccion }
        async getDataTransactionById() {
            var app = this;
            try {
                let response = await axios.post("/getDataTransactionById", {
                    id: app.id,
                });
                console.log(response);
                app.dataTransaction = response.data[0];
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
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
  