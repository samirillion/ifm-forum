/* ------------------------------------------------------------------------
  * LC Switch
  * superlight jQuery plugin improving forms look and functionality
  *
  * @version:   1.1
  * @requires:  jQuery v1.7 or later
  * @author:    Luca Montanari (LCweb)
  * @website:   https://lcweb.it
  
  * Licensed under the MIT license
------------------------------------------------------------------------- */

(function(a){if("undefined"!=typeof a.fn.lc_switch)return!1;a.fn.lc_switch=function(f,g){a.fn.lcs_destroy=function(){a(this).each(function(){a(this).parents(".lcs_wrap").children().not("input").remove();a(this).unwrap()});return!0};a.fn.lcs_on=function(){a(this).each(function(b,c){var e=a(this).parents(".lcs_wrap"),d=e.find("input");if(e.find(".lcs_on").length)return!0;"function"==typeof a.fn.prop?d.prop("checked",!0):d.attr("checked",!0);d.trigger("lcs-on");d.trigger("lcs-statuschange");e.find(".lcs_switch").removeClass("lcs_off").addClass("lcs_on");
if(e.find(".lcs_switch").hasClass("lcs_radio_switch")){var f=d.attr("name");e.parents("form").find("input[name="+f+"]").not(d).lcs_off()}});return!0};a.fn.lcs_off=function(){a(this).each(function(){var b=a(this).parents(".lcs_wrap"),c=b.find("input");if(!b.find(".lcs_on").length)return!0;"function"==typeof a.fn.prop?c.prop("checked",!1):c.attr("checked",!1);c.trigger("lcs-off");c.trigger("lcs-statuschange");b.find(".lcs_switch").removeClass("lcs_on").addClass("lcs_off")});return!0};a.fn.lcs_toggle=
function(){a(this).each(function(){if(a(this).hasClass("lcs_radio_switch"))return!0;a(this).is(":checked")?a(this).lcs_off():a(this).lcs_on()});return!0};return this.each(function(){if(!a(this).parent().hasClass("lcs_wrap")){var b="undefined"==typeof f?"Text":f,c="undefined"==typeof g?"Link":g;b=b?'<div class="lcs_label lcs_label_on">'+b+"</div>":"";c=c?'<div class="lcs_label lcs_label_off">'+c+"</div>":"";var e=a(this).is(":disabled")?!0:!1;var d=a(this).is(":checked")?" lcs_on":" lcs_off";e&&(d+=" lcs_disabled");
b='<div class="lcs_switch '+d+'"><div class="lcs_cursor"></div>'+b+c+"</div>";!a(this).is(":input")||"checkbox"!=a(this).attr("type")&&"radio"!=a(this).attr("type")||(a(this).wrap('<div class="lcs_wrap"></div>'),a(this).parent().append(b),a(this).parent().find(".lcs_switch").addClass("lcs_"+a(this).attr("type")+"_switch"))}})};a(document).ready(function(){a(document).on("click tap",".lcs_switch:not(.lcs_disabled)",function(f){a(this).hasClass("lcs_on")?a(this).hasClass("lcs_radio_switch")||a(this).lcs_off():
a(this).lcs_on()});a(document).on("change",".lcs_wrap input",function(){a(this).is(":checked")?a(this).lcs_on():a(this).lcs_off()})})})(jQuery);
