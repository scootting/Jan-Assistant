<template>
  <el-row>
    <label v-show="!edit" >{{ text }}</label>
    <el-input v-show="edit" v-model="editText" placeholder="" size="normal" clearable></el-input>
    <el-button :icon="'el-icon-caret-'+(showOptions ? 'left' : 'right')" type="info" size="mini" circle @click="showOptions = !showOptions"></el-button>
    <span v-show="showOptions">
      <el-button v-show="edit" icon="el-icon-circle-check" type="success" size="mini" circle @click="onSave()"></el-button>
      <el-button v-show="!edit" icon="el-icon-edit" type="default" size="mini" circle @click="onEdit()"></el-button>
      <el-button v-show="edit" icon="el-icon-circle-close" type="warning" size="mini" circle @click="onCancel()"></el-button> 
      <el-button icon="el-icon-delete" type="danger" size="mini" circle @click="onDelete()"></el-button> 
    </span>
  </el-row>
</template>

<script>
export default {
  name: 'DetalleItem',
  props: {
    value: {
      type: String,
      default: '',
    }
  },
  data(){
    return {
      editText: '',
      text: '',
      edit: false,
      showOptions: false,
    };
  },
  watch: {
    value(newVal){
      this.text =newVal;
      this.editText =newVal;
    },
  },
  created(){
    this.text = this.value;
  },
  methods:{
    onEdit(){
      this.editText = this.text;
      this.edit = true;
    },
    onSave(){
      this.text = this.editText;
      this.edit = false;
      this.$emit('input',this.text);
      this.$emit('change',this.text);
    },
    onCancel(){
      this.edit = false;
    },
    onDelete(){
      this.$emit('delete',this.text);
    }
  }
}
</script>

<style>

</style>