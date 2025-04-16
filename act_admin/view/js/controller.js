/*
* ----------------------
* | Cookie Controller  |
* ----------------------
*
* @Author: A.I Raju
* @Version: 1.0
* @Copyright: 2024
* @License: MIT
*
* Since V1.0
*/
var Cookie = {
	
	/*
	* Get Cookie
	* Ref: https://www.w3schools.com/js/js_cookies.asp
	*/
	get: function( cname ){
		let name = cname + "=";
		let decodedCookie = decodeURIComponent( document.cookie );
		let ca = decodedCookie.split(';');
		for( let i = 0; i <ca.length; i++ ) {
			let c = ca[i];
			while ( c.charAt(0) == ' ' ) {
				c = c.substring(1);
			}
			if ( c.indexOf(name) == 0 ) {
				return c.substring( name.length, c.length );
			}
		}
		return "";
	},
	
	/*
	* Set Cookie
	* Ref: https://www.w3schools.com/js/js_cookies.asp
	*/
	set: function( cname, cvalue, exdays = 7, path = '/', domain = false ){
	  const d = new Date();
	  d.setTime(d.getTime() + (exdays*24*60*60*1000));
	  let expires = "expires="+ d.toUTCString();
	  
	  if( domain === false ){
		  domain = new URL(document.location).host.replace('www.', '');
	  }
	  
	  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/;domain="+domain+";Secure";
	},
	
	/*
	* Has Cookie
	*/
	has: function( cookie_name ){
		if( this.get( cookie_name ) ){ return true; }else{ return false; }
	}
	
}

/*
* ---------------------------
* | End Cookie Controller   |
* ---------------------------
*/



/*
* -----------------------------
* | Url Controller Functions  |
* -----------------------------
*
* @Author: A.I Raju
* @Version: 1.0
* @Copyright: 2024
* @License: MIT
*
* Since V1.0
*/
var Url = {
	
	/*
	* Get Query Parameters From Url.
	*/
	get: function( param )
	{
		var currentUrl = window.location.search.substring(1),
		urlVar = currentUrl.split('&'),
		paramName,
		i;
		for (i = 0; i < urlVar.length; i++) {
			paramName = urlVar[i].split('=');

			if (paramName[0] === param) {
				return paramName[1] === undefined ? false : decodeURIComponent(paramName[1]);
			}
		}
		return false;
	},
	
	/*
	* Get Clean Page Url Without parameters.
	*/
	getCleanUrl: function( url = false ){
		var current_url = new URL( (url === false ? window.location.href:url) );
		var clean_url = current_url.href.replace(current_url.hash, '');
		return clean_url.replace(current_url.search, '');
	},
	
	/* Current Page Url */
	currentUrl: function(){
		return window.location.href;
	},
	
	/* Url.parseUrl(); return object */
	parseUrl: function( url = false){
		if( !url )
		{
			return window.location;
		}else{
			return new URL( url );
		}
	},	
	
	/* Url Encode Function */
	encode: function( url )
	{
		return url !=='' ? encodeURIComponent( url ):false;
	},
	
	/* Url Encode Function */
	decode: function( url )
	{
		return url !=='' ? decodeURIComponent( url ):false;
	},
	
	/* URL Redirector */
	go: function( url )
	{
		if( url !=='' )
		{
			window.location = url;
		}else{
			return false;
		}
	},
	
	/*
	* Url.isValidUrl(); to detect the url is valid or not.
	* Ref: https://stackoverflow.com/questions/5717093/check-if-a-javascript-string-is-a-url
	*/
	isValidUrl: function( string ) {
	  var res = string.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
	  return ( res !== null )
	},
	
	/*
	* Url.getValidUrl();
	* This function is used for making the url valid.
	* If any url don't have protocol, hostname etc. then this function will make it valid.
	* Version 1
	* Since V1
	*/
	getValidUrl: function( url ){
		if( url.substring(0, 2) === '//' ) url = 'http:'+url;
		if( url[0] === '/' ) url = 'http:/'+url;
		
		if( url.substring(0, 7) === 'http://' || url.substring(0, 8) === 'https://' ){
			
			url = url;
			
		}else{
			var currentPageUrl = this.parseUrl( this.currentUrl() );
			url = 'http://'+currentPageUrl.hostname+'/'+url;
		}
		
		return url;
	}
}

/*
* ------------------------
* | End Url Controller   |
* ------------------------
*/