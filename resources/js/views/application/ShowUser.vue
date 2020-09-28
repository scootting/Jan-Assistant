<template>
<div>
    <el-card class="box-card">
        <div slot="header" class="clearfix">
            <span>perfil</span>
            <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
        </div>

        <div>
            <el-row>
                <el-col :span="24">
                    <el-form ref="form" :model="user" label-width="260px">
                        <el-form-item size="small" label="numero de identificacion" prop="nodip">
                            <el-input size="small" v-model="user.nodip" disabled></el-input>
                        </el-form-item>
                        <el-form-item size="small" label="apellidos y nombres" prop="descripcion">
                            <el-input size="small" v-model="user.descripcion" disabled></el-input>
                        </el-form-item>
                        <el-form-item size="small" label="nombre de usuario" prop="usuario">
                            <el-input size="small" v-model="user.usuario" disabled></el-input>
                        </el-form-item>
                        <el-form-item size="small" label="clave de usuario" prop="clave">
                            <el-input style="width: 84%" size="small" v-model="user.clave" disabled show-password></el-input>
                            <el-button style="width: 15%" size="small" type="primary" @click.prevent="centerDialogVisible = true" plain>Cambiar</el-button>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-row>
        </div>
    </el-card>
    <!--                  
      *** - dialogo usando ElementUI - ***
    -->
    <el-dialog title="contraseña" :visible.sync="centerDialogVisible" width="50%" center>
        <el-form ref="form" :model="userPassword" label-width="190px">
            <el-form-item label="actual contraseña" prop="pass">
                <el-input type="password" v-model="userPassword.pass" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="nueva contraseña" prop="newPass">
                <el-input type="password" v-model="userPassword.newPass" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Confirmar contraseña" prop="checkPass">
                <el-input type="password" v-model="userPassword.checkPass" autocomplete="off"></el-input>
            </el-form-item>
        </el-form>
        <span slot="footer" class="dialog-footer">
            <el-button @click="centerDialogVisible = false" size="small">Cancelar</el-button>
            <el-button type="primary" @click="changePassword" size="small">Confirmar</el-button>
        </span>
    </el-dialog>
</div>
</template>

<script>
export default {
    name: "Profile",
    data() {
        return {
            messages: {},
            user: this.$store.state.user,
            centerDialogVisible: false,
            userPassword: {
                pass: '',
                newPass: '',
                checkPass: ''
            },
        };
    },
    mounted() {},
    methods: {
        test() {
            alert("bienvenido al modulo");
        },
        changePassword() {

        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped>
.el-card {
    background: #ffffff;
}

.el-form {
    padding-left: 120px;
    padding-right: 120px;
    padding-top: 60px;
}

.el-dialog>.el-form {
    padding-left: 30px;
    padding-right: 30px;
    padding-top: 20px;

}
</style>
