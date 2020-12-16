@php
	if ($filter != 'admin') {
    	$tempName = str_replace($filter.'_', '', $tempName); 
	}

    $tempName = ucwords(str_replace('_', ' ', $tempName));
    
    if ($tempName == 'Show') {
        $tempName = 'Preview';
    }elseif ($tempName == 'Update') {
        $tempName = 'Edit';
    }elseif ($tempName == 'Revise') {
    	$tempName = 'Revisions';
    }

    echo $tempName;
    
@endphp