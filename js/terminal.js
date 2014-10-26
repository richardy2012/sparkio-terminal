var variableInputs = document.querySelectorAll( '.credentials input' );

function getStringWidth( string )
{
	var ruler = document.querySelector(".width-placeholder");
	ruler.innerHTML = string;

	var rulerLeftPos 	= 	ruler.getBoundingClientRect().left;
	var rulerRightPos 	= 	ruler.getBoundingClientRect().right;

	rulerWidth	=	rulerRightPos - rulerLeftPos;

	return rulerWidth;
}

[].forEach.call( variableInputs, function( element ) {

	if ( element.value != '' )
	{
		element.style.width = getStringWidth( element.value ) + 'px' ;
	}

	element.addEventListener( "keydown", function( ev ) {

		var element = ev.srcElement;

		console.log( ev );

		var characterCode = ev.which;

		if ( ev.shiftKey ) 
		{ 
			characterCode += 32 
		}

		stringValue = element.value;

		if ( characterCode > 31 )
		{
			stringValue += String.fromCharCode( characterCode )
		}
	
		if ( stringValue.length < 1 )
		{
			element.style.width	=	"";
		}
		else
		{
			element.style.width =  getStringWidth( stringValue ) + 'px' ; 
		}
	} )

})

// Scroll function
function updateScroll( element )
{
	element.scrollTop = element.scrollHeight;
}

updateScroll( document.querySelector('.output') );

// Input history function
var inputElement 	= 	document.querySelector('[name=input]');
var currentInputKey	=	false;

inputElement.addEventListener("keydown", function( ev ) {

	var input = ev.srcElement;

	var key 	=	ev.which;

	if ( key == 27 )
	{
		input.value	=	'';
	}

	if ( key == 38 || key == 40 )
	{
		var topNumber		=	( key == 38 ) ? inputHistory.length - 1 : 0;
		var bottomNumber	=	( key == 38 ) ? 0 : inputHistory.length - 1;
		var product			=	( key == 38 ) ? -1 : 1;	

		if ( currentInputKey === false )
		{
			currentInputKey = topNumber;
		}
		else
		{
			if ( currentInputKey === bottomNumber )
			{
				currentInputKey	= topNumber;
			}
			else
			{
				currentInputKey = currentInputKey + ( 1 * product);
			}
		}



		var val = inputHistory[ currentInputKey ];
		input.value = val;
	}

		
		
		
		console.log( inputHistory );

	console.log(ev.which);

})
