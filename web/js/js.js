// JavaScript Document
//global var
var CONSTDEFAULTDESC = "1";
var INTERVAL_IMAGE_FAST = 20;
var INTERVAL_IMAGE_SLOW = 5000;
var TAB_IMAGE_PATH = Array();
var TAB_IMAGE_VUE_PATH = new Object();
var IMAGE_PATH_CURRENT ="";
var idInterval;
var i_image = 0;
var i_image_2em_solution = 1;
var FILTREID = null;
//var DEBUT_IMAGE_A_CHARGER = 1;
//var NB_IMAGES = 320;

function initializeConstante(){
    DEBUT_IMAGE_A_CHARGER++;
    var diff = NB_IMAGES - DEBUT_IMAGE_A_CHARGER;
    if(diff<MAX_IMAGE_A_CHARGER){
        var a = MAX_IMAGE_A_CHARGER-diff;
        DEBUT_IMAGE_A_CHARGER -= a;
        
    }
    i_image_2em_solution = DEBUT_IMAGE_A_CHARGER;
    
}

//function swith effet on text rec
function swithEffectOnTextRec(key){
	$("#rec1 p").removeClass("selected");
	$("#rec2 p").removeClass("selected");
	$("#rec3 p").removeClass("selected");
	$("#rec4 p").removeClass("selected");
	$("#rec"+key+" p").addClass("selected");
}
// remove effet on texte rec
function removeAllEffetTextRec(){
	$("#rec1 p").removeClass("selected");
	$("#rec2 p").removeClass("selected");
	$("#rec3 p").removeClass("selected");
	$("#rec4 p").removeClass("selected");
	$("#rec5 p").removeClass("selected");
}
function chargeImage(fast){
    
    var newSRC = PATHIMAGEGENERAL+i_image_2em_solution+'.jpg';
    $.get(newSRC, function(data){
        $("#imageFondLoading").attr("src",newSRC);
         $("#imageFond").hide();
         $("#imageFond").show('fade');
         if(i_image_2em_solution<(MAX_IMAGE_A_CHARGER+DEBUT_IMAGE_A_CHARGER)) i_image++;
         else i_image_2em_solution=DEBUT_IMAGE_A_CHARGER;    
    });
    /*$("#imageFondLoading").attr("src",newSRC);
    if(fast==undefined){
        $("#imageFond").hide();
        $("#imageFond").show('fade');
    }*/ 
    //if(i_image<(MAX_IMAGE_A_CHARGER+DEBUT_IMAGE_A_CHARGER)) i_image++;
    //else i_image=DEBUT_IMAGE_A_CHARGER;    
}

// function to get article 
function getActicleData(){
$("#getActicle span").hide();
$("#getActicle img").show();
var url = $(this).attr("href");
$.ajax({
   type: "POST",
   url: url,
   data:{"idFiltre":FILTREID},
   error:function(msg){
     alert( "Error !: " + msg );
   },
   success:displayActicle

});
return false;
}

// affiche un article default 
function displayDefaultActicle(response){
    $("#acticle").html(response);
}


// affiche un article
function displayActicle(response){

    $("#acticle").html(response);
    $("#getActicle span").show();
    $("#getActicle img").hide();
    //$("#acticle").show('highlight');
     themeUpdate();
     updateProgTheme();
     
}
function updateProgTheme(){
    var prog = $('#themeUpdate_texte').attr("title");
    var bx =  $('#themeUpdate_bg').attr("title");
    var id =  $('#themeUpdate_id').attr("title");
    $("a#theme_"+id).parent().css("background-position-x",bx);
    $("a#theme_"+id+" span span").text(prog+" %");
    $("a#theme_"+id).parent().effect('pulsate',{times:2},500);
    
}
//modifie le theme
function themeUpdate(o){
     var colorBackground;
     var qtipTheme;
     var boutonTheme;
    if(o==undefined){ 
        colorBackground =  $("#colorBackground").attr("title");
        qtipTheme =  $("#colorBulle").attr("title");
        boutonTheme =  $("#colorBouton").attr("title");
    }
    else{ // creation article mode
         $(this).children("span").each(function(){
           var name = $(this).attr("title");
           var id = $(this).attr("id");
           if("colorBackground"==id) colorBackground = name;
           else if("colorBulle"==id) qtipTheme = name;
           else  boutonTheme = name;
           
         })
         var id = $(this).attr("id");
        $("#form_color:hidden").attr("value",id);
    }
    $("#header").css("background-color", colorBackground);
    $(".menuGauche").css("background-color", colorBackground);
    $(".acticleParagraphe1 span").css("background-color", colorBackground);
    $(".acticleParagraphe2 span").css("background-color", colorBackground);
    $("textarea").css("background-color", colorBackground);
    $(".formCreationArticle p").css("background-color", colorBackground);
    
    $("#getActicle").attr("class",boutonTheme);
    $('a[title]').qtip({
        position: {
		adjust: {
			x: -55, y: 5,
			mouse: true,
			method: 'flip',
			resize: true
		}
        },
        style: {
                
		classes: ''+qtipTheme+' ui-tooltip-shadow'
        }})
}
function afficherImage(i){
$(".imageFond").hide();
$("#imageFondLoading").hide();
$("#imageFond"+i).show('fade');   
}
/*function chargerImage(i,path){
//$("#imageFondConteneur").append('<img style="position:absolute" id="imageFond'+i+'" class="imageFond" src="'+path+'" />');
TAB_IMAGE_VUE_PATH[""+i+""] = path;
}*/
function chargerImages(i,i_image){
    
        var url = PATHIMAGEGENERAL+i_image+".jpg";
        $.get(url,
        function(data){
            $("#imageFondConteneur").append('<img style="position:absolute" id="imageFond'+i+'" class="imageFond" src="'+url+'" />');
            TAB_IMAGE_PATH.push(url);
        });
        /*$.ajax({
        type: "POST",
        url: url,
        success:function(response){
            TAB_IMAGE_PATH.push(url);
        }
        })*/
}


// size on object 
Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

function traitementImage(){
    if(Object.size(TAB_IMAGE_VUE_PATH)==MAX_IMAGE_A_CHARGER){
        afficherImage(i_image);
        i_image++;
        if(i_image==MAX_IMAGE_A_CHARGER) i_image = 0;
        
    }
    var imageChanger = false;
    var i = 0;
    while(imageChanger==false && i<TAB_IMAGE_PATH.length){
        var imageVue = TAB_IMAGE_VUE_PATH[''+i+''];
        if(imageVue==undefined){            
            imageChanger=true;
            TAB_IMAGE_VUE_PATH[""+i+""] = TAB_IMAGE_PATH[i];
            //chargerImage(i,TAB_IMAGE_PATH[i]);
            afficherImage(i);
        }
        else i++;
    }
}
function traitementHistorique(){
    var url = $(this).attr("href");
    var id =  $(this).attr("id");
    $("img#"+id).show();
    $("a#"+id).hide();
    $.ajax({
    type: "POST",
    url:url,
    /*error:function(msg){
        alert( "Error !: " + msg );
    },*/
    success:function(response){
     $("img#"+id).hide();
     $("a#"+id).show();
     $("#acticle").html(response);
     themeUpdate(); 
    }
    });
return false;    
}
function traitementUpdateHistorique(){
    $("#updateHistorique").hide();
    $("#imgLoadingHistorique").show();
    var url = $(this).attr("href");
    $.ajax({
    type: "POST",
    url:url,
    success:function(response){
         $("#imgLoadingHistorique").hide();
         $("#updateHistorique").show();
         if(response!=""){
            $("#ullisteHistorique").html(response);
         }
    }});

return false;
}
function traitementTheme(){
    $(".erreur_theme").hide();
    var id =  $(this).attr("id");
    var idnum = id.split('_');
    var url = $(this).attr("href");
    $("img#"+id).show();
    $("a#"+id).hide();
    $(this).hide();
    $.ajax({
    type: "POST",
    url:url,
    data:{"id":idnum[1]},
    /*error:function(msg){
        alert( "Error !: " + msg );
    },*/
    success:function(response){
        $("img#"+id).hide();
        $("a#"+id).show();
        if(response!="-1"){
            $("#rec2 span").hide();
            $("#theme_ok").show();
            $("#rec2 span").text(response);
            $("#rec2 span").show("highlight",300);
            FILTREID = idnum[1];
            
        }
        else{
            FILTREID = null;
            $("#rec2 span").text("NA");
            $("#theme_ko").show();
        }
    }

    });
    return false;
}
function annuleFiltre(){
    FILTREID = null;
    $("#rec2 span").text("NA");
}

function test(){
    for(var i=0;i<MAX_IMAGE_A_CHARGER;i++){
    chargerImages(i,DEBUT_IMAGE_A_CHARGER);
    DEBUT_IMAGE_A_CHARGER++;
    }
}
// js utilsié when doc ready 
jQuery(document).ready(function(){
initializeConstante();
setTimeout("test()",1);
//setInterval('chargeImage()',5000);

idInterval = setInterval('traitementImage()', 5000);
$("#ullisteTheme a").click(traitementTheme);
$("#ullisteHistorique a").click(traitementHistorique);

$(".conteneurPanelColor li").click(themeUpdate);
        $("a#updateHistorique").click(traitementUpdateHistorique);
        $("a#getActicle").click(getActicleData);

	 $(".recGlobal").mouseover(function(){
		removeAllEffetTextRec();
		$(this).addClass("selected");
	 })
	 $(".recGlobal").mouseout(function(){
	 	$(this).removeClass("selected");
		
	 })
         
         $("#rec2").click(annuleFiltre);

	 $("#titleTheme").click(function(){
		$("ul#ullisteHistorique").hide("blind");
                $("ul#ullisteTheme").toggle("blind");
	 })
        $("#titleHistorique").click(function(){
                $("ul#ullisteTheme").hide("blind");
		$("ul#ullisteHistorique").toggle("blind");
	 })
         

    
 $('li[title]').qtip({
	position: {
                my: 'left center',
		adjust: {
			x: +10, y: -15,
			mouse: false,
			method: 'flip',
			resize: true
		}
        },
        style: {
                
		classes: 'ui-tooltip-dark ui-tooltip-shadow',
		tip: {
			corner: true,
			mimic: false,
			width: 8,
			height: 8,
			border: true,
			offset: 0
		}
                
	}
	
	});
        
 $('a[title]').qtip({
	position: {
                my: 'top left',
		adjust: {
			x: -10, y: 0,
			mouse: false,
			method: 'flip',
			resize: true
		}
        },
        style: {
                
		classes: 'ui-tooltip-dark ui-tooltip-shadow',
		tip: {
			corner: true,
			mimic: false,
			width: 8,
			height: 8,
			border: true,
			offset: 0
		}
                
	}
	
	});        

});
