<template>
    <div>
        <el-row :gutter="20" type="flex" justify="space-around">
            <el-col v-for="img in list" :key="img.key" :span="4" :offset="0">
                {{img.label}}
                <el-image
                style="width: 100%; height: 100px;"
                :src="images[img.key]"
                fit="contain"
                @click.native="onClickImg(img)"></el-image>
            </el-col>
        </el-row>
        <div v-if="loadImg">
            <el-row :gutter="20" type="flex" justify="center">
                {{loadImg.label}}
            </el-row>
            <el-row :gutter="20" type="flex" justify="center">
                <image-load :info="info" @on-success="onLoadImg" />
            </el-row>
            <el-row :gutter="20" type="flex" justify="center">
                <el-button type="danger" size="default" @click="loadImg = null">Cancelar</el-button>
            </el-row>
        </div>
    </div>
</template>

<script>
import imageLoad from './imageLoad.vue';
export default {
  components: { imageLoad },
    name: 'LoadImgs',
    props: ['info'],
    data(){
        return {
            list: [
                {
                    key: 'img_fro',
                    label: 'frontal',
                },
                {
                    key: 'img_izq',
                    label: 'izquierda',
                },
                {
                    key: 'img_der',
                    label: 'derecha',
                },
                {
                    key: 'img_sup',
                    label: 'superior',
                },
                {
                    key: 'img_post',
                    label: 'posterior',
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
    methods: {
        onChange(){
            this.$emit('change',this.images);
        },
        onLoadImg(resp){
            this.images[this.loadImg.key]=resp.path;
            this.loadImg = null;
            this.onChange()
        },
        onClickImg(imgName){
            if(!this.loadImg)
                this.loadImg = imgName;
        }
    }
}
</script>

<style>

</style>