<?php $this->tplDisplay("inc_header.php", ['menu'=>'Testimonials','sPageTitle'=>$aTestimonial['name']]); ?>

	<h2>{$aTestimonial.name} - <small>{$aTestimonial.sub_name}</small></h2>

	<blockquote>{$aTestimonial.text}</blockquote>

<?php $this->tplDisplay("inc_footer.php"); ?>
