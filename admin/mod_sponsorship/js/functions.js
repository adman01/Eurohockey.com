
function delete_item(sesid,id,name,filter,filter2,list_number,id_type) {
		if (confirm ("Delete from sponsorship '"+name+"' ?")) 
				document.location=("sponsorship_action.php?action=item_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_type="+id_type);
		}
function delete_ads(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete ads '"+name+"' ?")) 
				document.location=("sponsorship_ads_action.php?action=delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}


function delete_sponsorship(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete ads '"+name+"' ?")) 
				document.location=("sponsorship_action.php?action=delete_sponsorship&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}		