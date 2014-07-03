/*
---
MooTools: the javascript framework

web build:
 - http://mootools.net/more/e45781a08eb2f17fed7296ba5808309f

packager build:
 - packager build More/Element.Forms

copyrights:
  - [MooTools](http://mootools.net)

licenses:
  - [MIT License](http://mootools.net/license.txt)
...
*/

MooTools.More={version:"1.5.0",build:"73db5e24e6e9c5c87b3a27aebef2248053f7db37"};(function(){var e={a:/[àáâãäåăą]/g,A:/[ÀÁÂÃÄÅĂĄ]/g,c:/[ćčç]/g,C:/[ĆČÇ]/g,d:/[ďđ]/g,D:/[ĎÐ]/g,e:/[èéêëěę]/g,E:/[ÈÉÊËĚĘ]/g,g:/[ğ]/g,G:/[Ğ]/g,i:/[ìíîï]/g,I:/[ÌÍÎÏ]/g,l:/[ĺľł]/g,L:/[ĹĽŁ]/g,n:/[ñňń]/g,N:/[ÑŇŃ]/g,o:/[òóôõöøő]/g,O:/[ÒÓÔÕÖØ]/g,r:/[řŕ]/g,R:/[ŘŔ]/g,s:/[ššş]/g,S:/[ŠŞŚ]/g,t:/[ťţ]/g,T:/[ŤŢ]/g,u:/[ùúûůüµ]/g,U:/[ÙÚÛŮÜ]/g,y:/[ÿý]/g,Y:/[ŸÝ]/g,z:/[žźż]/g,Z:/[ŽŹŻ]/g,th:/[þ]/g,TH:/[Þ]/g,dh:/[ð]/g,DH:/[Ð]/g,ss:/[ß]/g,oe:/[œ]/g,OE:/[Œ]/g,ae:/[æ]/g,AE:/[Æ]/g},d={" ":/[\xa0\u2002\u2003\u2009]/g,"*":/[\xb7]/g,"'":/[\u2018\u2019]/g,'"':/[\u201c\u201d]/g,"...":/[\u2026]/g,"-":/[\u2013]/g,"&raquo;":/[\uFFFD]/g},c={ms:1,s:1000,m:60000,h:3600000},b=/(\d*.?\d+)([msh]+)/;
var a=function(h,j){var g=h,i;for(i in j){g=g.replace(j[i],i);}return g;};var f=function(g,i){g=g||"";var j=i?"<"+g+"(?!\\w)[^>]*>([\\s\\S]*?)</"+g+"(?!\\w)>":"</?"+g+"([^>]+)?>",h=new RegExp(j,"gi");
return h;};String.implement({standardize:function(){return a(this,e);},repeat:function(g){return new Array(g+1).join(this);},pad:function(g,j,i){if(this.length>=g){return this;
}var h=(j==null?" ":""+j).repeat(g-this.length).substr(0,g-this.length);if(!i||i=="right"){return this+h;}if(i=="left"){return h+this;}return h.substr(0,(h.length/2).floor())+this+h.substr(0,(h.length/2).ceil());
},getTags:function(g,h){return this.match(f(g,h))||[];},stripTags:function(g,h){return this.replace(f(g,h),"");},tidy:function(){return a(this,d);},truncate:function(g,h,k){var j=this;
if(h==null&&arguments.length==1){h="…";}if(j.length>g){j=j.substring(0,g);if(k){var i=j.lastIndexOf(k);if(i!=-1){j=j.substr(0,i);}}if(h){j+=h;}}return j;
},ms:function(){var g=b.exec(this);if(g==null){return Number(this);}return Number(g[1])*c[g[2]];}});})();Element.implement({tidy:function(){this.set("value",this.get("value").tidy());
},getTextInRange:function(b,a){return this.get("value").substring(b,a);},getSelectedText:function(){if(this.setSelectionRange){return this.getTextInRange(this.getSelectionStart(),this.getSelectionEnd());
}return document.selection.createRange().text;},getSelectedRange:function(){if(this.selectionStart!=null){return{start:this.selectionStart,end:this.selectionEnd};
}var e={start:0,end:0};var a=this.getDocument().selection.createRange();if(!a||a.parentElement()!=this){return e;}var c=a.duplicate();if(this.type=="text"){e.start=0-c.moveStart("character",-100000);
e.end=e.start+a.text.length;}else{var b=this.get("value");var d=b.length;c.moveToElementText(this);c.setEndPoint("StartToEnd",a);if(c.text.length){d-=b.match(/[\n\r]*$/)[0].length;
}e.end=d-c.text.length;c.setEndPoint("StartToStart",a);e.start=d-c.text.length;}return e;},getSelectionStart:function(){return this.getSelectedRange().start;
},getSelectionEnd:function(){return this.getSelectedRange().end;},setCaretPosition:function(a){if(a=="end"){a=this.get("value").length;}this.selectRange(a,a);
return this;},getCaretPosition:function(){return this.getSelectedRange().start;},selectRange:function(e,a){if(this.setSelectionRange){this.focus();this.setSelectionRange(e,a);
}else{var c=this.get("value");var d=c.substr(e,a-e).replace(/\r/g,"").length;e=c.substr(0,e).replace(/\r/g,"").length;var b=this.createTextRange();b.collapse(true);
b.moveEnd("character",e+d);b.moveStart("character",e);b.select();}return this;},insertAtCursor:function(b,a){var d=this.getSelectedRange();var c=this.get("value");
this.set("value",c.substring(0,d.start)+b+c.substring(d.end,c.length));if(a!==false){this.selectRange(d.start,d.start+b.length);}else{this.setCaretPosition(d.start+b.length);
}return this;},insertAroundCursor:function(b,a){b=Object.append({before:"",defaultMiddle:"",after:""},b);var c=this.getSelectedText()||b.defaultMiddle;
var g=this.getSelectedRange();var f=this.get("value");if(g.start==g.end){this.set("value",f.substring(0,g.start)+b.before+c+b.after+f.substring(g.end,f.length));
this.selectRange(g.start+b.before.length,g.end+b.before.length+c.length);}else{var d=f.substring(g.start,g.end);this.set("value",f.substring(0,g.start)+b.before+d+b.after+f.substring(g.end,f.length));
var e=g.start+b.before.length;if(a!==false){this.selectRange(e,e+d.length);}else{this.setCaretPosition(e+f.length);}}return this;}});