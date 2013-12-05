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
<aside class="grid__item one-third aside-infos aside-infos--bx <?php echo $params->get('moduleclass_sfx'); ?>">
	<h2 class="aside-infos__title"><?php echo $module->title; ?></h2>

    
    <?php // Est-ce qu'il y a un caroussel ? ?>
	<?php if($params->get('caroussel') == 1) : ?>
        <ul class="bxslider slider-recette">

        <?php foreach ($list as $item) : ?>

            <?php 
                $images = json_decode($item->images); 
            ?>

            <li class="slider-recette__layout-item">
            	<a href="<?php echo $item->link; ?>">
                    <img src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>" />
                    <div class="slider-recette__item-txt"><span><?php echo $item->title; ?></span></div>
              </a>
            </li>

        <?php endforeach; ?>

    	</ul>
    <?php endif; ?>
	
    <?php // Est-ce qu'il y a un sous titre ? ?>
	<?php if($params->get('subtitle') != '') : ?>
        <h3 class="aside-infos__subtitle"><?php echo $params->get('subtitle'); ?></h3>
    <?php endif; ?>

    <?php echo JHtml::_('string.truncate', strip_tags($featured->introtext, '<span><br>'), 120); ?>
	<p class="aside-infos__readmore"><a href="<?php echo $featured->link; ?>">Lire la suite</a></p>
</aside>