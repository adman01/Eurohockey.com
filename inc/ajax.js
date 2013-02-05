function input_table_clean(input_name)  {document.getElementById(input_name).value="";}

//-------------------------------showing leagues cross ID country / output_select box
function show_leagues(id_box)  {

    id_country=document.getElementById(id_box).value;
    id_country=id_country+"";
    position=id_country.indexOf("-");
    if (position<=0) position=id_country.length;
    id_country=id_country.substring(0,position);
    
    if (id_country>0){
        link='id_box=' + id_box+'&id_country='+id_country;
        postDataReturnXml("/inc/xml/xml_leagues_by_country_id.php",link,get_leagues_by_country_id);
		    return false; 
		}
		return false; 
}

function show_leagues_col_right(id_box)  {

    id_country=document.getElementById(id_box).value;
    if (id_country>0){
        link='id_box=' + id_box+'&id_country='+id_country;
        postDataReturnXml("/inc/xml/xml_leagues_by_country_id.php",link,get_leagues_by_country_id_col_right);
		    return false; 
		}
		return false; 
}

function get_leagues_by_country_id(xml) {
    var select_box="";
    var xml_items = xml.getElementsByTagName('items');
    id_box=xml_items[0].getAttribute('id_box');
    country_name=xml_items[0].getAttribute('country_name');
    var items = xml.getElementsByTagName('item');
    if (items.length>0){
    select_box=select_box+'<select name="id_league" id="'+id_box+'_form_select" class="league_select">';
    select_box=select_box+'<option value="">- select league -</option>';
    for (var i=0; i < items.length; i++) {
       id_league=items[i].getAttribute('id_league');
       name_league=items[i].firstChild.data;
       url_league=items[i].getAttribute('url');
       item_format='<option value="'+url_league+'">'+name_league+'</option>';
       select_box=select_box+item_format;
    }
    }else{
       select_box=select_box+'<option value="">any league not found</option>';
    }
    select_box=select_box+'</select>';
    id_box1=id_box+"_box";
    id_box2=id_box+"_select";
    document.getElementById(id_box1).className='';
    document.getElementById(id_box2).innerHTML=select_box;
    
}

function get_leagues_by_country_id_col_right(xml) {
    var select_box="";
    var xml_items = xml.getElementsByTagName('items');
    id_box=xml_items[0].getAttribute('id_box');
    country_name=xml_items[0].getAttribute('country_name');
    var items = xml.getElementsByTagName('item');
    if (items.length>0){
    select_box=select_box+'<select name="id_league" class="league_select" id="'+id_box+'_select2" onchange="show_statistic_page(\''+id_box+'\',0)">';
    select_box=select_box+'<option value="">- select league -</option>';
    for (var i=0; i < items.length; i++) {
       id_league=items[i].getAttribute('id_league');
       name_league=items[i].firstChild.data;
       url_league=items[i].getAttribute('url');
       item_format='<option value="'+url_league+'">'+name_league+'</option>';
       select_box=select_box+item_format;
    }
    }else{
       select_box=select_box+'<option value="">any league not found</option>';
    }
    select_box=select_box+'</select>';
    id_box1=id_box+"_box";
    id_box2=id_box+"_select";
    document.getElementById(id_box1).className='';
    document.getElementById(id_box2).innerHTML=select_box;
    
}

function show_leagues_set_selected(id_box,id_league)  {
    if (id_league>""){
      select_form=document.getElementById(id_box);
      //alert(id_box);
      for (var i=0; i < select_form.length; i++) {
        if (select_form[i].value == id_league) {
          select_form[i].selected = true;
        }
      }
    }
}

function show_statistic_page(id_box,id_season)  {
    
    url_league=document.getElementById(id_box+"_select2").value;
    if (url_league!=""){
        switch(id_box)
        {
          case "leagues_standings":
            window.location.href = "/league/"+url_league+"#standings";
          break;
          case "leagues_fixtures":
            window.location.href = "/league/"+url_league;
          break;
          case "leagues_leaders":
            window.location.href = "/stats/league/"+id_season+"/"+url_league;
          break;
        }
         
        return false; 
		}
		return false; 
}

//-------------------------------showing leagues cross ID country / output_table
function show_leagues_table(id_box,search,id_country)  {
    var link;
    if (id_country==0) {
      id_country=document.getElementById(id_box).value;
    }else{
     
      select_form=document.getElementById("leagues_table");
      for (var i=0; i < select_form.length; i++) {
        if (select_form[i].value == id_country) {
          select_form[i].selected = true;
        }
      }
      
    }
    if (id_country>0 || search.length>2){
        link='id_box=' + id_box+'&id_country='+id_country+'&search='+search;
        document.getElementById("ajax_loading").className="";
        postDataReturnXml("/inc/xml/xml_leagues_by_country_id_table.php",link,get_leagues_by_country_id_table);
		    return false; 
		}
		return false; 
}

function leagues_table_search(id_box)  {
    
    search=document.getElementById("league_search").value;
    if (search.length>1){
        show_leagues_table(id_box,search);
        return false; 
		}
		return false; 
}


//-------------------------------showing tournaments / output_table
function show_tournaments_table(id_box,search,id_country)  {
    var link;
    link='id_box=' + id_box+'&id_country='+id_country+'&search='+search;
    document.getElementById("ajax_loading").className="";
    postDataReturnXml("/inc/xml/xml_leagues_tournaments.php",link,get_leagues_by_country_id_table);
    return false; 
}


function get_leagues_by_country_id_table(xml) {
    document.getElementById("ajax_loading").className="hidden";
    var table_body="";
    var xml_items = xml.getElementsByTagName('items');
    id_box=xml_items[0].getAttribute('id_box');
    
    var items = xml.getElementsByTagName('item');
    table_body=table_body+'<table class="tablesorter basic" id="myTable">';
              table_body=table_body+'<thead>';
                  table_body=table_body+'<tr>';
                    table_body=table_body+'<th class="hidden" valign="top">&nbsp;</th>';
                    table_body=table_body+'<th class="hidden" valign="top">&nbsp;</th>';
                    table_body=table_body+'<th class="number center" valign="top">&nbsp;</th>';
                    table_body=table_body+'<th class="" valign="top">Name</th>';
                    table_body=table_body+'<th class="number" valign="top">Youth</th>';
                    table_body=table_body+'<th class="number" valign="top">Clubs</th>';
                    table_body=table_body+'<th class="link" colspan="2" valign="top">&nbsp;</th>';
                  table_body=table_body+'</tr>';
              table_body=table_body+'</thead>';
    if (items.length>0){
    
    for (var i=0; i < items.length; i++) {
       id_league=items[i].getAttribute('id_league');
       name_league=items[i].firstChild.data;
       url_league=items[i].getAttribute('url');
       club_count=items[i].getAttribute('club_count');
       english_name=items[i].getAttribute('english_name');
       country=items[i].getAttribute('country');
       youth=items[i].getAttribute('youth');
       stats_season=items[i].getAttribute('stats_season');
       order=items[i].getAttribute('order');
       order_categories=items[i].getAttribute('order_categories');
       
       if (i%2==0) {strStyle="";} else {strStyle="dark";}
       table_body=table_body+'<tr>';
        table_body=table_body+'<td class="hidden" valign="top">'+order+'</td>';
        table_body=table_body+'<td class="hidden" valign="top">'+order_categories+'</td>';
        table_body=table_body+'<td class="number center" valign="top">'+country+'</td>';
        table_body=table_body+'<td valign="top"><a href="/league/'+url_league+'" title="Show league '+name_league+'"><strong>'+name_league+'</strong></a></td>';
        table_body=table_body+'<td class="number" valign="top">'+youth+'</td>';
        if (club_count>1) name_items_correct="clubs"; else  name_items_correct="club";
        table_body=table_body+'<td class="number" valign="top"><a href="/clubs.html?id_league='+id_league+'" title="Show clubs from '+name_league+'"><strong>'+club_count+'</strong>&nbsp;'+name_items_correct+'</a></td>';
        table_body=table_body+'<td class="link right" valign="top">';
        if (stats_season>0) table_body=table_body+'<span class="link"><a href="/stats/league/'+stats_season+'/'+url_league+'" title="Player stats for league: '+name_league+'">Show&nbsp;player&nbsp;stats&raquo;</a></span>&nbsp;&nbsp;|&nbsp;&nbsp;';
        table_body=table_body+'<span class="link"><a href="/league/'+url_league+'" title="Show league '+name_league+'">Show&nbsp;details&raquo;</a></span></td>';
       table_body=table_body+'</tr>';
    }
    
    
    }else{
       table_body=table_body+'<tr><td colspan="10" valign="top" class="center bold">No league found.</td></tr>';
    }
     table_body=table_body+'</table>'; 

    id_box=id_box+"_box";
    document.getElementById(id_box).innerHTML=table_body;
    if (items.length>0){
      sorting_leagues_table();
    }
   
}

function sorting_leagues_table()  {

$(document).ready(function(){
	$.tablesorter.addParser({
	            id: 'dd.mm.yyyy',
	            is: function(s) {
	                return false;
	            },
	            format: function(s) {
	                s = '' + s; //Make sure it's a string
	                var hit = s.match(/(\d{1,2})\.(\d{1,2})\.(\d{4})/);
	                if (hit && hit.length == 4) {
	                    return hit[3] + hit[2] + hit[1];
	                }
	                else {
	                    return s;
	                }
	            },
	            type: 'text'
     });
     
     $.tablesorter.addParser({ 
	   
	    id: 'number', 
        is: function(s) { 
       
            return /(Kč|Sk|CZK|USD|EUR|AUD|GBP|PLN|SKK){1}$/.test(s);
        }, 
        format: function(s) { 
            return $.tablesorter.formatFloat(s.replace(new RegExp(/[^0-9.]/g),""));
        }, 
        type: 'numeric' 
      }); 
	   
		$.tablesorter.defaults.sortList = [[0,0],[1,0],[3,0]];
		$("table").tablesorter({
			headers: {
			  5: { sorter: 'number'},
			  6: { sorter: false }
			},
      widgets: ['zebra']
		});
	});
}


//-------------------------------showing clubs cross ID country / output_table
function show_leagues_select(id_box,id_country,boolShowTable)  {
    var link;
    if (id_country==0) {
      id_country=document.getElementById(id_box).value;
    }else{
     
      select_form=document.getElementById(id_box);
      for (var i=0; i < select_form.length; i++) {
        if (select_form[i].value == id_country) {
          select_form[i].selected = true;
        }
      }
      
    }
    
    if (id_country>0){
        link='id_box=' + id_box+'&id_country='+id_country+'&boolShowTable='+boolShowTable;
        postDataReturnXml("/inc/xml/xml_leagues_by_country_id.php",link,get_club_league_select);
		    return false; 
		}
		return false; 
}

function get_club_league_select(xml) {
    var select_box="";
    var xml_items = xml.getElementsByTagName('items');
    id_box=xml_items[0].getAttribute('id_box');
    country_name=xml_items[0].getAttribute('country_name');
    country_id=xml_items[0].getAttribute('country_id');
    countClubs=xml_items[0].getAttribute('countClubs');
    boolShowTable=xml_items[0].getAttribute('boolShowTable');
    var items = xml.getElementsByTagName('item');
    if (items.length>0){
    select_box=select_box+'<select name="'+id_box+'_id_league" id="'+id_box+'_id_league" onchange="show_club_table(\''+id_box+'\',\'\',0)">';
    select_box=select_box+'<option value="">- Select league -</option>';
    for (var i=0; i < items.length; i++) {
       id_league=items[i].getAttribute('id_league');
       name_league=items[i].firstChild.data;
       url_league=items[i].getAttribute('url');
       num_clubs=(items[i].getAttribute('num_clubs'))*1;
       if (num_clubs>1) {strClubName='clubs';} else {strClubName='club';}
       item_format='<option value="'+id_league+'"> '+name_league+' ('+num_clubs+' '+strClubName+')</option>';
       select_box=select_box+item_format;
    }
    }else{
       select_box=select_box+'<option value="">any league not found</option>';
    }
    select_box=select_box+'</select>';
    if (countClubs>1) {strClubName='clubs';} else {strClubName='club';}
    select_box=select_box+' <a class="bold link" href="javascript:void(0)" onclick="show_club_table_by_country(\''+id_box+'\','+country_id+')" title="Show all '+countClubs+' '+strClubName+' from '+country_name+'">or show all '+countClubs+' '+strClubName+' from '+country_name+'</a>';
    
    if (boolShowTable==1){show_club_table_by_country(id_box,country_id);}
    
    id_box=id_box+"_select";
    document.getElementById(id_box).innerHTML=select_box;
    
}


function show_club_table(id_box,search,id_league)  {
    var link;
    if (id_league==0) {
      id_league=document.getElementById(id_box+'_id_league').value;
    } 
    
    if (id_league>0 || search.length>1){
        link='id_box=' + id_box+'&id_league='+id_league+'&search='+search;
        document.getElementById("ajax_loading").className="";
        postDataReturnXml("/inc/xml/xml_clubs_by_league_id_table.php",link,get_club_by_league_id_table);
		    return false; 
		}
		return false; 
}

function show_club_table_by_country(id_box,id_country)  {
    var link;
    if (id_country>0){
        link='id_box=' + id_box+'&id_country='+id_country;
        document.getElementById("ajax_loading").className="";
        postDataReturnXml("/inc/xml/xml_clubs_by_league_id_table.php",link,get_club_by_league_id_table);
		    return false; 
		}
		return false; 
}

function club_table_search(id_box)  {
    
    search=document.getElementById("club_search").value;
    if (search.length>1){
        show_club_table(id_box,search,0);
        return false; 
		}
		return false; 
}

function get_club_by_league_id_table(xml) {
    var table_body="";
    document.getElementById("ajax_loading").className="hidden";
    var xml_items = xml.getElementsByTagName('items');
    id_box=xml_items[0].getAttribute('id_box');
    
    var items = xml.getElementsByTagName('item');
    
    table_body=table_body+'<table class="tablesorter basic" id="myTable">';
              table_body=table_body+'<thead>';
                  table_body=table_body+'<tr>';
                    table_body=table_body+'<th class="number center" valign="top">&nbsp;</th>';
                    table_body=table_body+'<th class="" valign="top">Name</th>';
                    table_body=table_body+'<th class="" valign="top">Status</th>';
                    table_body=table_body+'<th class="link" colspan="2" valign="top">&nbsp;</th>';
                  table_body=table_body+'</tr>';
              table_body=table_body+'</thead>';
    if (items.length>0){
    
    for (var i=0; i < items.length; i++) {
       id_league=items[i].getAttribute('id_league');
       name_club=items[i].firstChild.data;
       url_club=items[i].getAttribute('url');
       country=items[i].getAttribute('country');
       status=items[i].getAttribute('status');
       stats_season=items[i].getAttribute('stats_season');
       
       if (i%2==0) {strStyle="";} else {strStyle="dark";}
       table_body=table_body+'<tr>';
        table_body=table_body+'<td class="number center" valign="top">'+country+'</td>';
        table_body=table_body+'<td valign="top"><a href="/club/'+url_club+'" title="Show club '+name_club+'"><strong>'+name_club+'</strong></a></td>';
        table_body=table_body+'<td valign="top">'+status+'</td>';
        table_body=table_body+'<td class="link right" valign="top">';
        if (stats_season>0) {table_body=table_body+'<span class="link"><a href="/stats/club/'+stats_season+'/'+url_club+'" title="Player stats for club: '+name_club+'">Show&nbsp;player&nbsp;stats&raquo;</a></span>&nbsp;&nbsp;|&nbsp;&nbsp;';}
        table_body=table_body+'<span class="link"><a href="/club/'+url_club+'" title="Show club '+name_club+'">Show&nbsp;details&raquo;</a></span></td>';
       table_body=table_body+'</tr>';
    }
    
    
    }else{
       table_body=table_body+'<tr><td colspan="10" valign="top" class="center bold">No club found.</td></tr>';
    }
     table_body=table_body+'</table>'; 

    id_box=id_box+"_box";
    document.getElementById(id_box).innerHTML=table_body;
    if (items.length>0){
      sorting_club_table();
    }
   
}

function sorting_club_table()  {

$(document).ready(function(){
	$.tablesorter.addParser({
	            id: 'dd.mm.yyyy',
	            is: function(s) {
	                return false;
	            },
	            format: function(s) {
	                s = '' + s; //Make sure it's a string
	                var hit = s.match(/(\d{1,2})\.(\d{1,2})\.(\d{4})/);
	                if (hit && hit.length == 4) {
	                    return hit[3] + hit[2] + hit[1];
	                }
	                else {
	                    return s;
	                }
	            },
	            type: 'text'
     });
     
     $.tablesorter.addParser({ 
	   
	    id: 'number', 
        is: function(s) { 
       
            return /(Kč|Sk|CZK|USD|EUR|AUD|GBP|PLN|SKK){1}$/.test(s);
        }, 
        format: function(s) { 
            return $.tablesorter.formatFloat(s.replace(new RegExp(/[^0-9.]/g),""));
        }, 
        type: 'numeric' 
      }); 
	   
		$.tablesorter.defaults.sortList = [[1,0]];
		$("table").tablesorter({
			headers: {
			  3: { sorter: 'number'},
			  5: { sorter: false }
			},
      widgets: ['zebra']
		});
	});
}



