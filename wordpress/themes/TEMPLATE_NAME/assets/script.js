import{c as a}from"./common.js";const n=""+new URL("js.png",import.meta.url).href;a();console.log("console aaa");const c=document.querySelector("#canvas"),e=c.getContext("2d"),o=new Image(300,300);o.src=n;o.addEventListener("load",()=>{e==null||e.drawImage(o,0,0,300,300)});
