(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0e2154"],{"7cd6":function(e,t,a){"use strict";a.r(t);var o=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("el-form",{ref:"form",staticStyle:{width:"800px"},attrs:{model:e.form,"label-width":"100px",size:"mini"}},[a("el-form-item",{attrs:{label:"发起人名称"}},[a("el-input",{model:{value:e.form.username,callback:function(t){e.$set(e.form,"username",t)},expression:"form.username"}})],1),a("el-form-item",{attrs:{label:"发起人头像"}},[a("el-upload",{staticClass:"avatar-uploader",attrs:{action:"/api/auth/upload","show-file-list":!1,"with-credentials":!0,"on-success":e.handleAvatarSuccess}},[e.form.portrait?a("img",{staticClass:"avatar",attrs:{src:e.form.portrait}}):a("i",{staticClass:"el-icon-plus avatar-uploader-icon"})]),a("el-input",{staticStyle:{"line-height":"0"},attrs:{type:"hidden"},model:{value:e.form.portrait,callback:function(t){e.$set(e.form,"portrait",t)},expression:"form.portrait"}})],1),a("el-form-item",{attrs:{label:"项目标题"}},[a("el-input",{attrs:{placeholder:"顶部标题"},model:{value:e.form.title,callback:function(t){e.$set(e.form,"title",t)},expression:"form.title"}})],1),a("el-form-item",{attrs:{label:"项目说明"}},[a("el-input",{attrs:{placeholder:"一大段说明那个......"},model:{value:e.form.introduction,callback:function(t){e.$set(e.form,"introduction",t)},expression:"form.introduction"}})],1),a("el-form-item",{attrs:{label:"需要资金"}},[a("el-input",{attrs:{placeholder:""},model:{value:e.form.need_amount,callback:function(t){e.$set(e.form,"need_amount",t)},expression:"form.need_amount"}})],1),a("el-form-item",{attrs:{label:"患者"}},[a("el-input",{attrs:{placeholder:"XXX"},model:{value:e.form.patient,callback:function(t){e.$set(e.form,"patient",t)},expression:"form.patient"}})],1),a("el-form-item",{attrs:{label:"所患疾病"}},[a("el-input",{attrs:{placeholder:"XXX"},model:{value:e.form.sickness,callback:function(t){e.$set(e.form,"sickness",t)},expression:"form.sickness"}})],1),a("el-form-item",{attrs:{label:"证明医院"}},[a("el-input",{attrs:{placeholder:"XXX医院"},model:{value:e.form.prove_hospital,callback:function(t){e.$set(e.form,"prove_hospital",t)},expression:"form.prove_hospital"}})],1),a("el-form-item",{attrs:{label:"收款人"}},[a("el-input",{attrs:{placeholder:"XXX(本人)"},model:{value:e.form.payee,callback:function(t){e.$set(e.form,"payee",t)},expression:"form.payee"}})],1),a("el-form-item",{attrs:{label:"年收入"}},[a("el-input",{attrs:{placeholder:"5"},model:{value:e.form.annual_income,callback:function(t){e.$set(e.form,"annual_income",t)},expression:"form.annual_income"}})],1),a("el-form-item",{attrs:{label:"金融资产"}},[a("el-input",{attrs:{placeholder:"2"},model:{value:e.form.financial_assets,callback:function(t){e.$set(e.form,"financial_assets",t)},expression:"form.financial_assets"}})],1),a("el-form-item",{attrs:{label:"房产数量"}},[a("el-input",{attrs:{placeholder:"1"},model:{value:e.form.house_count,callback:function(t){e.$set(e.form,"house_count",t)},expression:"form.house_count"}})],1),a("el-form-item",{attrs:{label:"房产价值"}},[a("el-input",{attrs:{placeholder:"20"},model:{value:e.form.house_value,callback:function(t){e.$set(e.form,"house_value",t)},expression:"form.house_value"}})],1),a("el-form-item",{attrs:{label:"房产状态"}},[a("el-input",{attrs:{placeholder:"已变卖/未变卖"},model:{value:e.form.house_status,callback:function(t){e.$set(e.form,"house_status",t)},expression:"form.house_status"}})],1),a("el-form-item",{attrs:{label:"是否有医保"}},[a("el-input",{attrs:{placeholder:"有医保/无医保"},model:{value:e.form.health_care,callback:function(t){e.$set(e.form,"health_care",t)},expression:"form.health_care"}})],1),a("el-form-item",{attrs:{label:"证明图片"}},[a("el-upload",{attrs:{action:"/api/auth/upload","list-type":"picture-card","file-list":e.project_image_list,"on-success":e.handleImageSuccess}},[a("i",{staticClass:"el-icon-plus"})])],1),a("el-form-item",[a("el-button",{attrs:{type:"primary"},on:{click:e.onSubmit}},[e._v("立即创建")]),a("el-button",[e._v("取消")])],1)],1)},l=[],s={data:function(){return{form:{id:0,username:"",portrait:"",title:"",introduction:"",patient:"",sickness:"",prove_hospital:"",payee:"",annual_income:"",financial_assets:"",house_count:"",house_value:"",house_status:"",health_care:"",need_amount:"",image_list:[]},project_image_list:[]}},methods:{onSubmit:function(){var e=this;this.$httpPost("admin/projectAdd",this.form).then(function(t){if(0===t.code)for(var a in e.$message({message:t.msg,center:!0,type:"success"}),e.form)e.form[a]="";else e.$message({message:t.msg,center:!0,type:"warning"})}).catch(function(){e.$message({message:"请求服务器异常, 请稍后再试",center:!0,type:"error"})})},handleAvatarSuccess:function(e,t){0===e.code&&(this.form.portrait=e.data.file)},handleImageSuccess:function(e,t){0===e.code&&this.form.image_list.push(e.data.file)}},mounted:function(){var e=this,t=this.$store.state.projectId;t>0&&this.$httpGet("project/info?id="+t).then(function(t){if(0===t.code){e.form=t.data;for(var a=e.form.image_list,o=0;o<a;o++)e.project_image_list.push({name:o,url:e.form.image_list[o]})}})}},r=s,i=a("2877"),n=Object(i["a"])(r,o,l,!1,null,"9b97d91e",null);t["default"]=n.exports}}]);
//# sourceMappingURL=chunk-2d0e2154.16ef671f.js.map