<?php
if(!empty($aPageMessages)) {
	foreach($aPageMessages as $aPageMessage) {
		echo '<div class="alert alert-'.$aPageMessage['type'].'">';
			if($aPageMessage['close']){ echo '<a class="close" data-dismiss="alert">×</a>'; }
			echo $aPageMessage['text'];
		echo '</div>';
	}
}
