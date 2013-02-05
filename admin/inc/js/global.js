// ------------------------------------------ funkce pro otevirani novych oken
function okno(soubor) {
   window.open(soubor,'','toolbar=yes,scrollbars=yes,location=yes,status=yes,resizable=1,menubar=yes')
}

function okno_set(soubor,width,height) {
   window.open(soubor,'','toolbar=no,scrollbars=yes,location=no,status=no,width='+width+',height='+height+',resizable=1')
}

// ------------------------------------------ funkce pro zaskrtnuti vsech polozek
function CheckAll(f,el) {
			for (var i=0;i<f.elements.length;i++)
			{
				var e=f.elements[i];
				if (e.id==el) e.checked=f.check_all.checked;
			}
		}

// ------------------------------------------ funkce pro zaskrtnuti vsech polozek
function CheckAll2(f,el,g) {
	for (var i=0;i<f.elements.length;i++)
	{
		var e=f.elements[i];
		if (e.id==el) {
		  e.checked=document.getElementById(g).checked;
    }
	}
}

// ------------------------------------------ zmeni tridu (class) objektu s danym ID
function ZmenTridu(trida,id)  {
  document.getElementById(id).className = trida;
}

// ----- odhlasovaci fce
function logout2(sesid) {
		if (confirm ("Log out from system ?")) 
				document.location=("/admin/login.php?action=logout&sesid="+sesid);
		}


//odpocitavani odhlaseni		
function logout(sesid, timeout)  {
  countdown(((timeout*60)*1000), sesid);
}

function countdown(timeout, sesid)  {
	timeout = timeout - 1000;
	strTimeout = timeout / 1000;
	strTimeoutMin = strTimeout / 60;
	strTimeoutMin = Math.floor(strTimeoutMin);
	strTimeoutSec = strTimeout - (strTimeoutMin*60);
	strTimeoutSec = Math.floor(strTimeoutSec);
	if (strTimeoutSec<'10') {
		strTimeoutSec = "0"+strTimeoutSec;
	}
	if (strTimeoutMin<'0') {
 		document.location=("/admin/login.php?action=logout&sesid="+sesid)
	} else {
		document.getElementById('autologout').innerHTML = "automatic log out "+' <b>'+strTimeoutMin+':'+strTimeoutSec+'</b>';
		timerID = setTimeout("countdown('"+timeout+"', '"+sesid+"')",1000);
	}
}

//zobrazi/schova idcko
function show_item_check(id){
   if (document.getElementById(id).className=='hidden')
      {document.getElementById(id).className='';} 
    else 
      {document.getElementById(id).className='hidden';}
  }