(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0ccbf6"],{"4ef5":function(e,t,r){"use strict";r.r(t);var s=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("el-form",{ref:"form",staticStyle:{width:"800px"},attrs:{model:e.form,"label-width":"100px",size:"mini"}},[r("el-form-item",{attrs:{label:"网站域名"}},[r("el-input",{attrs:{placeholder:""},model:{value:e.form.base_uri,callback:function(t){e.$set(e.form,"base_uri",t)},expression:"form.base_uri"}},[e._v(">")])],1),r("el-form-item",{attrs:{label:"微信AppId"}},[r("el-input",{attrs:{placeholder:"公众号AppId"},model:{value:e.form.wx_app_id,callback:function(t){e.$set(e.form,"wx_app_id",t)},expression:"form.wx_app_id"}})],1),r("el-form-item",{attrs:{label:"微信Secret"}},[r("el-input",{attrs:{placeholder:"公众号秘钥"},model:{value:e.form.wx_secret,callback:function(t){e.$set(e.form,"wx_secret",t)},expression:"form.wx_secret"}})],1),r("el-form-item",{attrs:{label:"微信MchId"}},[r("el-input",{attrs:{placeholder:"商户号ID"},model:{value:e.form.wx_mch_id,callback:function(t){e.$set(e.form,"wx_mch_id",t)},expression:"form.wx_mch_id"}})],1),r("el-form-item",[r("el-button",{attrs:{type:"primary"},on:{click:e.onSubmit}},[e._v("立即修改")]),r("el-button",[e._v("取消")])],1)],1)},a=[],o={data:function(){return{form:{base_uri:"",wx_secret:"",wx_app_id:"",wx_mch_id:""}}},methods:{onSubmit:function(){var e=this;this.$httpPost("admin/confAdd",this.form).then(function(t){0===t.code?e.$message({message:t.msg,center:!0,type:"success"}):e.$message({message:t.msg,center:!0,type:"warning"})}).catch(function(){e.$message({message:"请求服务器异常, 请稍后再试",center:!0,type:"error"})})}},activated:function(){var e=this;this.$httpGet("admin/sysConf").then(function(t){0===t.code&&(e.form=t.data)}).catch(function(){e.$message({message:"请求服务器异常, 请稍后再试",center:!0,type:"error"})})}},c=o,n=r("2877"),i=Object(n["a"])(c,s,a,!1,null,"14c5c791",null);t["default"]=i.exports}}]);
//# sourceMappingURL=chunk-2d0ccbf6.85c9880b.js.map