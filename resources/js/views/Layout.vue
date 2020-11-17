<template>
  <div>
    <el-card class="box-card">
      <h1>Hello, world!</h1>
      <p>
        Lionel This is a template for a simple marketing or informational
        website. It includes a large callout called a jumbotron and three
        supporting pieces of content. Use it as a starting point to create
        something more unique.
      </p>
      <el-button size="mini" plain @click="loadReportAssets()"
        >Default</el-button
      >
      <el-button type="primary" size="mini" plain>Primary</el-button>
      <el-button type="primary" icon="el-icon-edit" size="mini"></el-button>
      <el-button type="primary" icon="el-icon-share" size="mini"></el-button>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "Bienvenido",
  data() {
    return {
      messages: {},
      data: {},
    };
  },
  mounted() {},
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    loadReportAssets() {
      axios({
        url: "inventory/download",
        method: "GET",
        responseType: "blob",
      }).then((response) => {
        console.log(response);
        console.log("1");
        //let newBlob =
        let blob = new Blob([response.data], {
          type: "application/pdf",
        });
        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        link.download = "test.pdf";
        link.click();
      });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
