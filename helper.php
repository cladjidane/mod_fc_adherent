<?php
/**
 *
 *
 * @package     Joomla frontend
 * @subpackage  mod_fc_adherents
 * @author Fabien CANU <fabien.canu@gmail.com>
 */

// no direct access
defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.model' );

require_once JPATH_SITE.'/components/com_adherent/helpers/route.php';

JModelLegacy::addIncludePath( JPATH_SITE . '/components/com_adherent/models', 'AdherentModel' );

abstract class modFcAdherentsHelper {

    /**
     *
     *
     * @param type    $params
     * @return type
     */
    public static function getList( &$params ) {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();

        // Get an instance of the generic adherents model
        $model = JModelLegacy::getInstance( 'Adherents', 'AdherentModel', array( 'ignore_request' => true ) );

        $model->setState( 'list.start', 0 );
        $model->setState( 'list.limit', (int) 5 );

        $model->setState( 'filter.published', 1 );

        // Retrieve Content
        $items = $model->getItems();

        foreach ( $items as &$item ) {
            $item->readmore = strlen( trim( $item->fulltext ) );
            $item->slug = $item->id.':'.$item->alias;
            $item->catslug = $item->catid.':'.$item->category_alias;

            $item->link = JRoute::_( AdherentHelperRoute::getAdherentRoute( $item->slug, $item->catid ) );

            $item->introtext = JHtml::_( 'content.prepare', $item->introtext, '', 'mod_articles_news.content' );

            //new
            if ( !$params->get( 'image' ) ) {
                $item->introtext = preg_replace( '/<img[^>]*>/', '', $item->introtext );
            }
        }

        return $items;
    }

}
