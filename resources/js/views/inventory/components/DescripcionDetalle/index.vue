<template>
  <div>
    <div :key="'item-'+index" v-for="(item, index) in items">
      <el-divider v-if="index>0" />
      <detalle-item  v-model="items[index]" @change="onChange()" @delete="onDeleteItem(index)" />
    </div>
    <el-divider />
    <el-button type="primary" icon="el-icon-add" size="mini" style="width: 100%" @click="addItem()">Nuevo</el-button>
    
  </div>
</template>

<script>
import detalleItem from "./components/detalleItem.vue"

export default{
  name: 'DescripcionDetalle',
  components: {
    detalleItem,
  },
  props: {
    value: {
      type: String,
      default: '',
    }
  },
  data(){
    return {
      items: [],
    };
  },
  created(){
    this.divideString();
  },
  methods: {
    emitDataInString(){
      const itemsInString = this.items.join('|');
      this.$emit('input',itemsInString);
    },
    divideString(){
      this.items = this.value.split('|');  
    },
    addItem(){
      this.items.push('nuevo detalle');
    },
    onChange(){
      this.emitDataInString();
    },
    onDeleteItem(index){
      this.items.splice(index,1);
      this.emitDataInString();
    },
  },
}
</script>

<style>

</style>