<template>
<div>
    <el-card class="box-card">
        <div slot="header" class="clearfix">
            <span>manejo de perfiles</span>
            <el-button style="float: right; padding: 3px 0" type="text">nuevos perfiles</el-button>
        </div>
        <h1>perfiles</h1>
        <h5>
            esta pagina aun esta en desarrollo, haremos lo posible para que este funcional en el menor tiempo posible.
            <p>Gracias</p>
            <p>{{ user.usuario }}</p>
            <p>{{ user.gestion }}</p>
            <p>{{ user }}</p>
        </h5>
    </el-card>
</div>
</template>

<script>
export default {
    name: "Inventarios",
    data() {
        return {
            user: this.$store.state.user,
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
