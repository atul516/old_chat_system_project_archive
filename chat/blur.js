function changeColorBlue()
{
	document.getElementById('Layer2').style.background = '#73A6FF';	
}
function gotFocus()
{
	document.title = 'Gmail Inbox(1)';
	
	state = 'focus';
	
	played = 0 ;
}
function lostFocus()
{
	document.title = 'Sajith M.R Says...';
	state = 'nonfocus';
	
	played =  0 ;
	
	changeColorRed();
	
	alterTitle();	
}

function changeColorRed()
{
		document.getElementById('Layer2').style.background = '#FF8A00';
			
}

function alterTitle()
{	
	if(state =='nonfocus')	
	{
			if ( document.title == 'Gmail Inbox(1)')
				{					
					if(played == 0)
					{	
						soundManager.play('notify');
						played = 1;
					}					
					document.title = 'Sajith M.R Says...';					
				}	
			else
				document.title = 'Gmail Inbox(1)';				
			
			setTimeout("alterTitle()",3000);		
	}		
}