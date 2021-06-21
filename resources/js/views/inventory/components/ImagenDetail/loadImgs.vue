<template>
  <div>
    <el-row :gutter="20" type="flex" justify="space-around">
      <el-col v-for="img in list" :key="img.key" :span="4" :offset="0">
        {{ img.label }}
        <div style="width: 100%; height: 100px" @click="onClickImg(img)">
          <img
            v-if="images[img.key]"
            style="height: 100%; width: 100%; object-fit: cover"
            :src="images[img.key]"
            alt="NO SE PUDO CARGAR"
          />
          <div
            v-else
            style="
              width: 100%;
              height: 100px;
              display: flex;
              justify-content: center;
              align-items: center;
              background-color:#C6DFF6;
              color:black;"
          >NO ENCONTRADO
            
          </div>
        </div>
        <!-- <el-image
          style="width: 100%; height: 100px"
          :src="images[img.key]"
          fit="contain"
          :lazy="true"
          @click.native="onClickImg(img)"
        ></el-image> -->
      </el-col>
    </el-row> 
    <br/><br/>
    <div v-if="loadImg">
      <el-row :gutter="20" type="flex" justify="center">
        {{ loadImg.label }}
      </el-row>
      <br/>
      <el-row :gutter="20" type="flex" justify="center">
        <image-load :info="info" @on-success="onLoadImg" />
      </el-row>
      <el-row :gutter="20" type="flex" justify="center">
        <el-button type="danger" plain size="mini" @click="loadImg = null"
          >Cancelar</el-button
        >
      </el-row>
    </div>
  </div>
</template>

<script>
import imageLoad from "./imageLoad.vue";
export default {
  components: { imageLoad },
  name: "LoadImgs",
  props: ["info", "defaultValues"],
  data() {
    return {
      list: [
        {
          key: "img_fro",
          label: "frontal",
        },
        {
          key: "img_izq",
          label: "izquierda",
        },
        {
          key: "img_der",
          label: "derecha",
        },
        {
          key: "img_sup",
          label: "superior",
        },
        {
          key: "img_post",
          label: "posterior",
        },
      ],
      images: {
        img_fro: null,
        img_izq: null,
        img_der: null,
        img_sup: null,
        img_post: null,
      },
      loadingImg: false,
      loadImg: null,
    };
  },
  watch: {
    defaultValues(newVal) {
      this.list.forEach((e) => {
        if (newVal[e.key] != "") this.images[e.key] = newVal[e.key];
      });
      var lista = this.list;
      this.list = [];
      this.list = lista;
    },
  },
  methods: {
    onChange() {
      this.$emit("change", this.images);
    },
    onLoadImg(resp) {
      this.images[this.loadImg.key] = resp.path;
      this.loadImg = null;
      this.onChange();
    },
    onClickImg(imgName) {
      if (!this.loadImg) this.loadImg = imgName;
    },
  },
};
</script>

<style>
</style>