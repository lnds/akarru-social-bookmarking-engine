<script src="scripts/labels.js" type="text/javascript"></script>
<script type="text/javascript">
{literal}
<!---
function update_vote_div(div_name,user_id){
	post_id=div_name.replace(/vote_count_/g,"");
{/literal}
	ajaxRead("{#ajax_service#}?meme_id="+post_id+"&user_id="+user_id);
{literal}
	return false;
}

var url_to_go;
function goto_url(meme_id,url)
{
	url_to_go = url;
{/literal}
	ajaxCall("{#ajax_service_click#}?meme_id="+meme_id);
{literal}
}


function createRequestObject() {
	var ro;
    var browser = navigator.appName;
	if(window.XMLHttpRequest) {
			ro = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
        try {
        	ro = new ActiveXObject("Msxml2.XMLHTTP");
      	} catch(e) {
        	try {
          		ro = new ActiveXObject("Microsoft.XMLHTTP");
        	} catch(e) {
          		ro = false;
        	}
		}
	}
    return ro;
}

var xmlObj = createRequestObject();

function ajaxRead(file){
	xmlObj.open ('GET', file, true);
	xmlObj.onreadystatechange = handleResponse;
    xmlObj.send ("");
}

function ajaxCall(file){
	xmlObj.open ('GET', file, true);
	xmlObj.onreadystatechange = handleResponseNull;
    xmlObj.send ("");
}

function handleResponseNull()
{
	if(xmlObj.readyState == 4)
	{
	document.location = url_to_go;
}
}

function handleResponse(){	
	if(xmlObj.readyState == 4)
	{
			counter=xmlObj.responseXML.getElementsByTagName('data')[0].firstChild.data;
			post_id=xmlObj.responseXML.getElementsByTagName('meme_id')[0].firstChild.data;
			if(xmlObj.responseXML.getElementsByTagName('error').length > 0){
				alert(xmlObj.responseXML.getElementsByTagName('error')[0].firstChild.data);
			}
			else {
				div_name="vote_count_"+post_id;
				mydiv=document.getElementById(div_name);
				mydiv.innerHTML=parseInt(counter);
				div_name="vote_button_"+post_id;
				mydiv=document.getElementById(div_name);
				mydiv.innerHTML = '';
				div_name="vote_label_"+post_id;
				mydiv=document.getElementById(div_name);
				mydiv.innerHTML = '';
				
			}
    }
}
-->
</script>
{/literal}

