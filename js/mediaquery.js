var detectAndUseStylesheet=function(){var b=screen.width,d=document.getElementsByTagName("link"),c=new RegExp(b,"i"),e=false,a=[];for(ii in d){if(d[ii].href){if(d[ii].id){a.push(d[ii].id.match(/[0-9]+/i)[0]);if(d[ii].id.match(c)){document.getElementById("stylesheet-"+b).removeAttribute("media");e=true}}}}if(!e){for(ii in a){if(b<a[ii]){document.getElementById("stylesheet-"+a[ii]).removeAttribute("media");break}}}};window.attachEvent("onload",detectAndUseStylesheet);