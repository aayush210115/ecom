var JigoshopHelpers,jigoshop;jigoshop={},JigoshopHelpers=function(){function e(e){this.params=e}return e.prototype.params={assets:"",ajaxUrl:""},e.prototype.delay=function(e,r){return setTimeout(r,e)},e.prototype.getAssetsUrl=function(){return this.params.assets},e.prototype.getAjaxUrl=function(){return this.params.ajaxUrl},e.prototype.addMessage=function(e,r,t){var o;return o=jQuery(document.createElement("div")).attr("class","alert alert-"+e).html(r).hide(),o.appendTo(jQuery("#messages")),o.slideDown(),jigoshop.delay(t,function(){return o.slideUp(function(){return o.remove()})})},e.prototype.block=function(e,r){var t;return t=jQuery.extend({redirect:"",message:"",css:{padding:"5px",width:"auto",height:"auto",border:"1px solid #83AC31"},overlayCSS:{backgroundColor:"rgba(255, 255, 255, .8)"}},r),e.block({message:'<img src="'+this.params.assets+'/images/loading.gif" width="15" height="15" alt="'+t.redirect+'"/>'+t.message,css:t.css,overlayCSS:t.overlayCSS})},e.prototype.unblock=function(e){return e.unblock()},e}(),jQuery(function(){return jigoshop=new JigoshopHelpers(jigoshop_helpers)});