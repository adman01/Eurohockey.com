function delete_user(sesid,id,name) {
		if (confirm ("Delete user '"+name+"' ?")) 
				document.location=("users_action.php?action=delete&sesid="+sesid+"&id="+id);
		}
function delete_special_right(sesid,id,id_user,name) {
		if (confirm ("Delete right '"+name+"' ?")) 
				document.location=("users_special_rights_action.php?action=delete&sesid="+sesid+"&id="+id+"&id_user="+id_user);
		}
		
function active() {
    if (document.getElementById('inp-2').disabled==true){ document.getElementById('inp-2').disabled=false; }
      else  {document.getElementById('inp-2').disabled=true;}
    if (document.getElementById('inp-3').disabled==true){ document.getElementById('inp-3').disabled=false; }
      else  {document.getElementById('inp-3').disabled=true;}
}
