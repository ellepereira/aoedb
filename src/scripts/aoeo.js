
var protopath;
var level = 0;
var rlevel = 0;


function logindiag(e)
{
	//var url = this.href;
    var dialog = $('<div style="display:none"></div>').appendTo('body');
    // load remote content
    dialog.load(
        '/aoeo/login_screen', 
        {},
        function (responseText, textStatus, XMLHttpRequest) {
            dialog.dialog({
    			autoOpen: true,
    			resizable: false,
    			height:240,
    			width: 500,
    			draggable: false,
    			modal: true,
    			title: e.target.title,
    			buttons: {
    				Cancel: function() {
    					$( this ).dialog( "close" );
    				}
    			}
    		});
        }
    );
    //prevent the browser to follow the link
    return false;
}

function auctionSell(dbid, ilevel)
{
	$('#overlay').fadeIn(500);
	$("#popup").css("display", "block");
	$("#popup").load("/auction/item/"+dbid+"/"+ilevel);
	$("#overlay").click(function(){$('#overlay').fadeOut(300); $("#popup").fadeOut(100);});
}

function expandItem(dbid, ilevel)
{
	$("#itemContainer").load("/traits/a_trait/"+dbid+"/"+ilevel+" #item");
}

function minimizeItem(dbid, ilevel)
{
	$("#itemContainer").load("/traits/a_minimized/"+dbid+"/"+ilevel+" #item");
}

function xmldiag(e, folder, file)
{
	//var url = this.href;
    var dialog = $('<div style="display:none"></div>').appendTo('body');
    // load remote content
    dialog.load(
        '/aoeo/axml/'+folder+'/'+file, 
        {},
        function (responseText, textStatus, XMLHttpRequest) {
            dialog.dialog({
    			autoOpen: true,
    			resizable: false,
    			height:760,
    			width: 700,
    			draggable: false,
    			modal: true,
    			title: 'XML File',
    			buttons: {
    				Cancel: function() {
    					$( this ).dialog( "close" );
    				}
    			}
    		});
        }
    );
    //prevent the browser to follow the link
    return false;
}

function auctionHouseCheckbox()
{
	$('#auctionreg').display();
}

function changelevel(nlevel)
{
	level = nlevel;
	
	if(level < 1)
		level = 1;
	else if(level > 40)
		level = 40;
	
	rlevel = level;
	var ie = $('#itemeffects');
	
	hidechangelevel();
	
	$('#rlevel').html('Required Level: '+level);
	$('#imglink').html('<a href="/i/'+dbid+'/'+level+'.png">img</a>');
	ie.html('loading new stats');
	ie.load('/traits/aeffects/'+dbid+'/'+level);
	
}

function hidechangelevel()
{
	$('#showchangelevel').html('');
}

function showchangelevel()
{
	var cl = $('#showchangelevel');
	if(!Modernizr.inputtypes.range)
	{
		cl.html("<input id='i' type='text' placeholder='1-40' size='2'/><input id='iok' type='button' value='ok' />");
	}
	else
	{
		cl.html("<input id='i' type='range' min=1 max=40 value='"+rlevel+"'/><input id='iok' type='button' value='ok' />");
		$('#i').change(function(e){ $('#rlevel').html('Required Level: '+ $('#i').val()); });
		$('#itemeffects').html('(level being changed - press OK)');
	}
	
	$('#iok').click(function(e){ changelevel($('#i').val()); });
}

function registerdiag(e)
{
	//var url = this.href;
    var dialog = $('<div style="display:none"></div>').appendTo('body');
    // load remote content
    dialog.load(
        '/aoeo/register_screen', 
        {},
        function (responseText, textStatus, XMLHttpRequest) {
            dialog.dialog({
    			autoOpen: true,
    			resizable: false,
    			height:340,
    			width: 500,
    			draggable: false,
    			modal: true,
    			title: e.target.title,
    			buttons: {
    				Cancel: function() {
    					$( this ).dialog( "close" );
    				}}
    		});
        }
    );
    //prevent the browser to follow the link
    return false;
}

$(function() {
	
	$('#logindiaglink').click(function(e){ logindiag(e); });
	$('#registerdiaglink').click(function(e){registerdiag(e);});
	$("#changelevel").click(function(e){showchangelevel();});
});


function loadproto()
{
	var xd = $('#xml');
	xd.load(protopath);
}