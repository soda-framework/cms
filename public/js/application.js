var Soda=function(){var t=!1,e={deleteButtons:"[data-delete-button]",formSubmitters:"[data-submits]",sidebarToggle:"[data-sidebar-toggle]",sidebar:".sidebar"},n=function(t){},a=function(){return $('meta[name="csrf-token"]').attr("content")},i=function(t,e){var n=$("<form></form>");n.attr("method","POST"),n.attr("action",t),$.each(e,function(t,e){var a=$("<input></input>");a.attr("type","hidden"),a.attr("name",t),a.attr("value",e),n.append(a)}),$(document.body).append(n),n.submit()},o=function(){$(e.deleteButtons).on("click",function(t){t.preventDefault(),s($(this))}),$(e.formSubmitters).on("click",function(){var t=$(this).data("submits");null!=$(this).data("publishes")&&$(t).find('input[name="status"]').val(1),$(t).submit()}),$(e.sidebarToggle).on("click",d)},r=function(){t===!1&&(t=!0,$.ajaxSetup({headers:{"X-CSRF-TOKEN":a()}}),$(".soda-wrapper, .main-content").css("min-height",$(window).height()),$(".nav-item-group.active .collapse").collapse("show"),Soda.queryString.tab?$('a[href="#tab_'+Soda.queryString.tab+'"]').tab("show"):$('.nav-tabs a[data-toggle="tab"]').first().tab("show"),o())},s=function(t,e){var n=t.attr("href"),o=$.extend({},{_token:a(),_method:"DELETE"},e);swal({title:"Are you sure?",text:"This action can not be reversed!",type:"warning",showCancelButton:!0,confirmButtonClass:"btn-danger",confirmButtonText:"Yes, delete it!",closeOnConfirm:!1},function(){n?i(n,o):t.closest("form").submit()})},u=function(t){$.ajax({url:Soda.urls.sort,type:"POST",data:t,success:function(t){t.errors&&n(t.errors)},error:function(){n("Something went wrong!")}})},d=function(t){t.preventDefault();var n=$(e.sidebar).hasClass("in");$(this).attr("aria-expanded",!n),$(e.sidebar).toggleClass("in"),$("body").toggleClass("sidebar-in").addClass("sidebar-transitioning"),setTimeout(function(){$("body").removeClass("sidebar-transitioning")},250)};return{initialize:r,confirmDelete:s,changePosition:u,toggleSidebar:d}}();$(function(){Soda.initialize()});