<script type="text/javascript">
	function addRow(search) {

		var selSearch = document.getElementById(search);

		var numberSearch = (selSearch.getElementsByTagName("INPUT")).length;
		if ( numberSearch > 4 )
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

</script>


<?php echo validation_errors(); ?>

<?php

    include 'lateral_bar.php';

    echo '<div class="search" id="search_div">' ;
    echo '<div class="search" id="search_box">' ;
	$attributes = array('class' => 'search', 'id' => 'search');
	echo form_open('scripts/index',$attributes);

    if ( $active_search === 0  )
    {
        $search_term['search0'] = null; 
        $search_term['comparation0'] = null;
        $search_term['field0'] = null;

    	$fields = array( 'ev.id' => 'Evolutions', 'en.name' => 'Environment', 'ac.name' => 'Schema', 'sc.name' => 'Script', 'sc.version' => 'Version' );
	    echo form_dropdown('search0',$fields,$search_term['search0'],'id=search0');
	
    	$comparation = array('=' => '=', 'like' => 'like', '<>' => 'is not', 'not like' => 'is not like');
	    echo form_dropdown('comparation0',$comparation,$search_term['comparation0'],'id=comparation0');
	
    	$field_data = array('name' => 'field0','id'=> 'field0','maxlength' => '50','size' => '40','style' => 'width:20%');
        echo form_input($field_data,$search_term['field0']);
    
        $more_search=array('name' => 'add_button', 'id' => 'add_button', 'type' => 'button', 'content' => '+');
        $js_add = 'onClick="addRow(\'search\')"';
        echo form_button('more_button','+',$js_add);

        $more_search=array('name' => 'remove_button', 'id' => 'remove_button', 'type' => 'button', 'content' => '-');
        $js_remove = 'onClick="removeRow(\'search\')"';
        echo form_button('remove_button','-',$js_remove);

        echo '<br id="break0">';
    }
    else
    {

#        var_dump($search_term);

        $size_search = count($search_term);

        foreach ($search_term as $var => $term)
        {
            $count=substr($var,-1);
        
            $attribute = 'id=' . $var;

            if (substr($var,0,6) === 'search')
            {
	            $fields = array( 'ev.id' => 'Evolutions', 'en.name' => 'Environment', 'ac.name' => 'Schema', 'sc.name' => 'Script', 'sc.version' => 'Version' );
                echo form_dropdown('search' . $count ,$fields,$search_term[$var],$attribute);

            };

            if (substr($var,0,11) === 'comparation')
            {
                $comparation = array('=' => '=', 'like' => 'like', '<>' => 'is not', 'not like' => 'is not like');
                echo form_dropdown('comparation' . $count,$comparation,$search_term[$var],$attribute);
            };

            if (substr($var,0,5) === 'field')
            {
                $field_data = array('name' => 'field' . $count ,'id'=> 'field' . $count, 'maxlength' => '100','size' => '50','style' => 'width:20%');
                echo form_input($field_data,$search_term[$var]);
                
                if ( $count > 0 )
                {
                    echo '<br id="break' . $count . '">';
                };
            };

            if (substr($var,0,5) === 'andOr')
            {
                $andOr = array('and' => 'and', 'or' => 'or');

                if ( $count > 0 )
                {
                    $attribute = 'id=' . $var . ' class="search_space"';
                };

                echo form_dropdown('andOr' . $count,$andOr,$search_term[$var],$attribute);
            };

            if ($count == 0 and substr($var,0,5) === 'field' )
            {
                $more_search=array('name' => 'add_button', 'id' => 'add_button', 'type' => 'button', 'content' => '+');
                $js_add = 'onClick="addRow(\'search\')"';
                echo form_button('more_button','+',$js_add);

                $more_search=array('name' => 'remove_button', 'id' => 'remove_button', 'type' => 'button', 'content' => '-');
                $js_remove = 'onClick="removeRow(\'search\')"';
                echo form_button('remove_button','-',$js_remove);
                echo '<br id="break' . $count . '">';
             };                

        };

    };


    echo '</div>' ; // search_box

	echo '<div  class="search" id="search_buttons">';
    echo form_submit('submit_search', 'Search Item');
    echo form_submit('reset_search', 'Reset Search');
    echo '</div>' ;

?>

     <div id="main" class="main" style="overflow-x:auto;">
    
        <?php 
            echo $script_table;
            
#            foreach ($evolutions as $evolutions_item)
#            {
#   		        print_r($evolutions_item);
    # 	        print_r(array_values($evolutions_item)); 
#            };
         ?>

    </div>
</div>  <!-- search _div -->
