(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0a35c5"],{"029f":function(e,t,o){"use strict";o.r(t);var i=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",[o("el-table",{staticStyle:{width:"100%"},attrs:{data:e.tableData}},[o("el-table-column",{attrs:{prop:"id",label:"#ID"}}),o("el-table-column",{attrs:{prop:"title",label:"标题"}}),o("el-table-column",{attrs:{prop:"username",label:"发起人"}}),o("el-table-column",{attrs:{prop:"create_at",label:"创建时间"}}),o("el-table-column",{attrs:{prop:"url",label:"分享链接"}}),o("el-table-column",{attrs:{label:"操作",width:"300px"},scopedSlots:e._u([{key:"default",fn:function(t){return[o("el-button",{attrs:{size:"small"},on:{click:function(o){return e.modify(t.row.id)}}},[e._v("修改")]),o("el-button",{attrs:{type:"warning",size:"small"},on:{click:function(o){return e.dynamic(t.row.id)}}},[e._v("新增动态")]),o("el-button",{attrs:{type:"success",size:"small"},on:{click:function(o){return e.prove(t.row.id)}}},[e._v("证明")]),o("el-button",{attrs:{type:"primary",size:"small"},on:{click:function(o){return e.order(t.row.id)}}},[e._v("捐款")])]}}])})],1),o("el-dialog",{attrs:{title:"证明",visible:e.dialogProveVisible},on:{"update:visible":function(t){e.dialogProveVisible=t}}},[o("el-form",{attrs:{model:e.proveForm}},[o("el-form-item",{attrs:{label:"与X关系","label-width":"80px"}},[o("el-input",{attrs:{placeholder:"同事/朋友/亲戚",autocomplete:"off"},model:{value:e.proveForm.relation,callback:function(t){e.$set(e.proveForm,"relation",t)},expression:"proveForm.relation"}})],1),o("el-form-item",{attrs:{label:"介绍","label-width":"80px"}},[o("el-input",{attrs:{placeholder:"亲眼所见.........",autocomplete:"off"},model:{value:e.proveForm.introduce,callback:function(t){e.$set(e.proveForm,"introduce",t)},expression:"proveForm.introduce"}})],1)],1),o("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[o("el-button",{on:{click:function(t){e.dialogProveVisible=!1}}},[e._v("取 消")]),o("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.doProve()}}},[e._v("确 定")])],1)],1),o("el-dialog",{attrs:{title:"捐款",visible:e.dialogOrderVisible},on:{"update:visible":function(t){e.dialogOrderVisible=t}}},[o("el-form",{attrs:{model:e.orderForm}},[o("el-form-item",{attrs:{label:"金额","label-width":"80px"}},[o("el-input",{attrs:{autocomplete:"off"},model:{value:e.orderForm.amount,callback:function(t){e.$set(e.orderForm,"amount",t)},expression:"orderForm.amount"}})],1)],1),o("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[o("el-button",{on:{click:function(t){e.dialogOrderVisible=!1}}},[e._v("取 消")]),o("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.doOrder()}}},[e._v("确 定")])],1)],1),o("el-dialog",{attrs:{title:"动态",visible:e.dialogDynamicVisible},on:{"update:visible":function(t){e.dialogDynamicVisible=t}}},[o("el-form",{attrs:{model:e.dynamicForm}},[o("el-form-item",{attrs:{label:"动态描述","label-width":"80px"}},[o("el-input",{attrs:{autocomplete:"off"},model:{value:e.dynamicForm.title,callback:function(t){e.$set(e.dynamicForm,"title",t)},expression:"dynamicForm.title"}})],1),o("el-form-item",{attrs:{label:"上传图片","label-width":"80px"}},[o("el-upload",{attrs:{action:"/api/auth/upload","list-type":"picture-card","on-success":e.handleImageSuccess}},[o("i",{staticClass:"el-icon-plus"})])],1)],1),o("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[o("el-button",{on:{click:function(t){e.dialogDynamicVisible=!1}}},[e._v("取 消")]),o("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.doDynamic()}}},[e._v("确 定")])],1)],1)],1)},r=[],a={data:function(){return{tableData:[],dialogOrderVisible:!1,dialogProveVisible:!1,dialogDynamicVisible:!1,orderForm:{project_id:0,amount:""},proveForm:{project_id:0,relation:"",introduce:""},dynamicForm:{project_id:0,image_list:[],title:""}}},methods:{loadList:function(){var e=this;this.$httpGet("admin/projectList",this.form).then(function(t){0===t.code&&(e.tableData=t.data.list)}).catch(function(){e.$message({message:"请求服务器异常, 请稍后再试",center:!0,type:"error"})})},order:function(e){this.orderForm.project_id=e,this.dialogOrderVisible=!0},prove:function(e){this.proveForm.project_id=e,this.dialogProveVisible=!0},dynamic:function(e){this.dynamicForm.project_id=e,this.dialogDynamicVisible=!0},modify:function(e){this.$store.commit("setProjectId",e),this.$router.push("/project/add")},doOrder:function(){var e=this;this.$httpPost("admin/orderAdd",this.orderForm).then(function(t){0===t.code?(e.$message({message:"新增成功",center:!0,type:"success"}),e.dialogOrderVisible=!1,e.orderForm={}):e.$message({message:t.msg,center:!0,type:"warning"})}).catch(function(){e.$message({message:"请求服务器异常, 请稍后再试",center:!0,type:"error"})})},doProve:function(){var e=this;this.$httpPost("admin/proveAdd",this.proveForm).then(function(t){0===t.code?(e.$message({message:"新增成功",center:!0,type:"success"}),e.dialogProveVisible=!1,e.proveForm={}):e.$message({message:t.msg,center:!0,type:"warning"})}).catch(function(){e.$message({message:"请求服务器异常, 请稍后再试",center:!0,type:"error"})})},doDynamic:function(){var e=this;this.$httpPost("admin/dynamicAdd",this.dynamicForm).then(function(t){0===t.code?(e.$message({message:"新增成功",center:!0,type:"success"}),e.dialogDynamicVisible=!1,e.dynamicForm={}):e.$message({message:t.msg,center:!0,type:"warning"})}).catch(function(){e.$message({message:"请求服务器异常, 请稍后再试",center:!0,type:"error"})})},handleImageSuccess:function(e,t){0===e.code&&this.dynamicForm.image_list.push(e.data.file)}},activated:function(){this.loadList()}},l=a,s=o("2877"),n=Object(s["a"])(l,i,r,!1,null,"59ff413f",null);t["default"]=n.exports}}]);
//# sourceMappingURL=chunk-2d0a35c5.82e83aa5.js.map