<template>
  <div>
    <el-dialog
      title="Warning"
      :visible.sync="centerDialogVisible"
      width="30%"
      center
    >
      <span
        >It should be noted that the content will not be aligned in center by
        default</span
      >
      <span slot="footer" class="dialog-footer">
        <el-button @click="centerDialogVisible=false">Cancel</el-button>
        <el-button type="primary" @click="centerDialogVisible=false"
          >Confirm</el-button
        >
      </span>
    </el-dialog>
  </div>
</template>
<script>
export default {
  name: "person",
  props: {
    centerDialogVisible: {
      type: Boolean,
      default:false
    },
  },
  computed: {
    disabled() {
      return false;
    },
  },
};
</script>

<style>
</style>