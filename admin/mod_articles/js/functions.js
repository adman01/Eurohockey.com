function delete_articles(sesid,id,name,filter,filter2,list_number) {
		if (confirm ("Delete article '"+name+"' ?")) 
				document.location=("articles_action.php?action=delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number);
		}
function delete_assign(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete assign  to item '"+name+"' ?")) 
				document.location=("articles_action.php?action=assign_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}
