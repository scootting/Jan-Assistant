<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Card name</span>
                <el-button style="float: right; padding: 3px 0" type="text"
                    >Operation button</el-button
                >
            </div>
            <h1>bienvenido/a</h1>
            <h5>
                esta pagina a sido intencionalmente puesta en blanco
            </h5>
        </el-card>
    </div>
</template>

<script>
export default {
    name: "Bienvenido",
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
