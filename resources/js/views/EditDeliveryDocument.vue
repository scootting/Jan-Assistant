<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Documento Editado</span>
                
            </div>
            <h1>Documento # </h1>
            <h5>
                esta pagina aun esta en desarrollo, haremos lo posible para que este funcional en el menor tiempo posible.
                <p>Gracias</p>
            </h5>
        </el-card>
    </div>
</template>

<script>
export default {
    name: "ActivosFijos",
    data() {
        return {
            messages: {},
            data: {}
        };
    },
    mounted() {},
    methods: {
        test() {
            alert("bienvenido al modulo");
        }
    }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
