<?php

/**
 * 
 * @package     Joomla frontend
 * @subpackage  mod_fc_articles
 * @author Fabien CANU <fabien.canu@gmail.com> 
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="adherent">
		
	<h2 class="mozaic__title">Les adhérents</h2>
	
	<ul class="bxslider slider-adherent">

	<?php foreach ($list as $item) : ?>

	    <li class="slider-recette__layout-item">
	    	<a href="<?php echo $item->link; ?>">
	            <img src="/images/6-<?php echo $item->dep . '/' . $item->photoProducteur; ?>" alt="<?php echo $item->nomPrenom; ?>" />
	            <div class="slider-recette__item-txt"><span><?php echo $item->nomFerme; ?></span></div>
	        </a>
	    </li>

	<?php endforeach; ?>

	</ul>

</div>