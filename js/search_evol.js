function addRow(search) {

		var selSearch = document.getElementById(search);

		var numberSearch = (selSearch.getElementsByTagName("INPUT")).length;
		if ( numberSearch > 5 )
		{
			exit;
		}

		var selTable = document.getElementById('search0');
		var selComparation = document.getElementById('comparation0');
		var selFieldData = document.getElementById('field0');

		var selClone1 = selTable.cloneNode(true);
		var selClone2 = selComparation.cloneNode(true);
		var selClone3 = selFieldData.cloneNode(true);	

		selClone1.id = 'search' + ( numberSearch );
		selClone2.id = 'comparation' + ( numberSearch );
		selClone3.id = 'field' + ( numberSearch );

		selClone1.name = 'search' + ( numberSearch );
		selClone2.name = 'comparation' + ( numberSearch );
		selClone3.name = 'field' + ( numberSearch );

	    selClone1.setAttribute("class", "search_space_js");
	    selClone2.setAttribute("class", "search_space_js");
	    selClone3.setAttribute("class", "search_space_js");

		// clean field search
		selClone3.value = '';
	
		// 	put broken between the searchs
		var broken = document.createElement("BR");
		broken.setAttribute("id","break" + (numberSearch));

		if ( document.getElementById('andOr1') == null )
		{	
			var andOr = document.createElement("SELECT");
	    	andOr.setAttribute("id", "andOr" + (numberSearch));
	    	andOr.setAttribute("name", "andOr" + (numberSearch));
    		document.body.appendChild(andOr);

	    	var optionAnd = document.createElement("option");
	    	optionAnd.setAttribute("value", "and");
	    	var tOptionAnd = document.createTextNode("and");
	    	optionAnd.appendChild(tOptionAnd);

	    	var optionOr = document.createElement("option");
	    	optionOr.setAttribute("value", "or");
	    	var tOptionOr = document.createTextNode("or");
	    	optionOr.appendChild(tOptionOr);

	   		andOr.appendChild(optionAnd);
    		andOr.appendChild(optionOr);

	        andOr.setAttribute("class", "search_space");
			
            selSearch.appendChild(andOr);
		}
		else 
		{
			var selAndOr = document.getElementById('andOr1');
			var selClone0 = selAndOr.cloneNode(true);
			selClone0.id = 'andOr' + ( numberSearch );
			selClone0.name = 'andOr' + ( numberSearch );
	        selClone0.setAttribute("class", "search_space");
			
			selSearch.appendChild(selClone0);
		};
		
		selSearch.appendChild(selClone1);
		selSearch.appendChild(selClone2);
		selSearch.appendChild(selClone3);
		selSearch.appendChild(broken);
};

function removeRow(search){
	
		var selSearch = document.getElementById(search);

		var numberSearch = ( ((selSearch.getElementsByTagName("INPUT")).length) - 1 );		
		
		var selAndOr = document.getElementById('andOr' + numberSearch);
		var selTable = document.getElementById('search' + numberSearch);
		var selComparation = document.getElementById('comparation' + numberSearch);
		var selFieldData = document.getElementById('field' + numberSearch);
		var selBreak = document.getElementById('break' + numberSearch);	

		selAndOr.remove();
		selTable.remove();
		selComparation.remove();
		selFieldData.remove();
		selBreak.remove();
};
