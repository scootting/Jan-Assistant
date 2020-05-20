<template>
    <div>
        <el-container>
            <el-header>
                <el-menu class="el-menu-demo" mode="horizontal">
                    <el-menu-item index="1">DIRECCION ADMINISTRATIVA Y FINANCIERA</el-menu-item>
                </el-menu>
            </el-header>
            <el-container>
                <el-aside width="200px">Barra lateral</el-aside>
                <el-container>
                    <el-main>
                        <el-row :gutter="20">
                            <el-col :span="24">
                                <el-card class="box-card">
                                    <h1>Hello, world!</h1>
                                    <p>
                                        This is a template for a simple
                                        marketing or informational website. It
                                        includes a large callout called a
                                        jumbotron and three supporting pieces of
                                        content. Use it as a starting point to
                                        create something more unique.
                                    </p>
                                    <el-button size="mini" plain
                                        >Default</el-button
                                    >
                                    <el-button type="primary" size="mini" plain
                                        >Primary</el-button
                                    >
                                </el-card>
                            </el-col>
                        </el-row>
                        <el-row :gutter="20">
                            <el-col :span="8">
                                <el-card class="box-card">
                                    <h5>Heading</h5>
                                    <p>
                                        Donec id elit non mi porta gravida at
                                        eget metus. Fusce dapibus, tellus ac
                                        cursus commodo, tortor mauris
                                        condimentum nibh, ut fermentum massa
                                        justo sit amet risus. Etiam porta sem
                                        malesuada magna mollis euismod. Donec
                                        sed odio dui.
                                    </p>
                                </el-card>
                            </el-col>
                            <el-col :span="8">
                                <el-card class="box-card">
                                    <h5>Heading</h5>
                                    <p>
                                        Donec id elit non mi porta gravida at
                                        eget metus. Fusce dapibus, tellus ac
                                        cursus commodo, tortor mauris
                                        condimentum nibh, ut fermentum massa
                                        justo sit amet risus. Etiam porta sem
                                        malesuada magna mollis euismod. Donec
                                        sed odio dui.
                                    </p>
                                    <el-button
                                        type="primary"
                                        icon="el-icon-edit"
                                    ></el-button>
                                    <el-button
                                        type="primary"
                                        icon="el-icon-share"
                                    ></el-button>
                                </el-card>
                            </el-col>
                            <el-col :span="8">
                                <el-card class="box-card">
                                    <h5>Heading</h5>
                                    <p>
                                        Donec id elit non mi porta gravida at
                                        eget metus. Fusce dapibus, tellus ac
                                        cursus commodo, tortor mauris
                                        condimentum nibh, ut fermentum massa
                                        justo sit amet risus. Etiam porta sem
                                        malesuada magna mollis euismod. Donec
                                        sed odio dui.
                                    </p>
                                </el-card>
                            </el-col>
                            <!--
                            <el-col :span="4" style="padding-top: 10px">
                                <el-card class="box-card">
                                    <h5>Heading</h5>
                                    <p>
                                        Donec id elit non mi porta gravida at
                                        eget metus. Fusce dapibus, tellus ac
                                        cursus commodo, tortor mauris
                                        condimentum nibh, ut fermentum massa
                                        justo sit amet risus. Etiam porta sem
                                        malesuada magna mollis euismod. Donec
                                        sed odio dui.
                                    </p>
                                </el-card>
                            </el-col>
                            -->
                        </el-row>
                        <el-row :gutter="20">
                            <el-col :span="4"
                                ><div class="grid-content bg-purple"></div
                            ></el-col>
                            <el-col :span="16"
                                ><div class="grid-content bg-purple"></div
                            ></el-col>
                            <el-col :span="4"
                                ><div class="grid-content bg-purple"></div
                            ></el-col>
                        </el-row>
                    </el-main>
                    <el-footer>Pie de página</el-footer>
                </el-container>
            </el-container>
        </el-container>
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
<style scoped>
@import url("https://fonts.googleapis.com/css?family=Roboto+Condensed");
html,
body {
    font-family: "Roboto Condensed", sans-serif;
}
#app {
    font-family: "Roboto Condensed", sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: left;
    /*color: #2c3e50;
  */
}

button,
input,
select,
textarea {
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
    color: inherit;
}

.el-aside {
    color: #333;
}

/* estilos revisados y aprobados*/
.el-header {
    color: #333;
    line-height: 60px;
    padding: 0px;
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
    min-height: 36px;
}
.row-bg {
    padding: 10px 0;
    background-color: #f9fafc;
}
</style>
