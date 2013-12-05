<?php

/**
 * 
 * @package     Joomla frontend
 * @subpackage  mod_fc_adherents
 * @author Fabien CANU <fabien.canu@gmail.com> 
 */

// no direct access
defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_content/helpers/route.php';

jimport('joomla.application.component.model');

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');

abstract class modFcAdherentsHelper {
    
    /**
     *
     * @param type $params
     * @return type 
     */
    public static function getList(&$params) {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();

        // Get an instance of the generic adherents model
        $model = JModelLegacy::getInstance('Adherents', 'ContentModel', array('ignore_request' => true));

        // Set application parameters in model
        $appParams = JFactory::getApplication()->getParams();
        $model->setState('params', $appParams);

        // Set the filters based on the module params
        $model->setState('list.start', 0);
        $model->setState('list.limit', (int) $params->get('count', 5));

        $model->setState('filter.published', 1);
        
        // Access filter
        $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
        $authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
        $model->setState('filter.access', $access);

        // Category filter
        $model->setState('filter.category_id', $params->get('catid', array()));

        // Filter by features
        $model->setState('filter.featured', $params->get('featured', ''));

        // Set ordering
        $ordering = $params->get('ordering', 'a.publish_up');
        $model->setState('list.ordering', $ordering);
        if (trim($ordering) == 'rand()') {
            $model->setState('list.direction', '');
        } else {
            $model->setState('list.direction', 'DESC');
        }

        //	Retrieve Content
        $items = $model->getItems();

        //var_dump($items);

        foreach ($items as &$item) {

            $item->readmore = (trim($item->fulltext) != '');
            $item->slug = $item->id . ':' . $item->alias;
            $item->catslug = $item->catid . ':' . $item->category_alias;

            if ($access || in_array($item->access, $authorised)) {
                // We know that user has the privilege to view the article
                $item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
                $item->linkText = JText::_('COM_CONTENT_READ_MORE_TITLE');
            } else {
                $item->link = JRoute::_('index.php?option=com_users&view=login');
                $item->linkText = JText::_('mod_lsa_adherents_READMORE_REGISTER');
            }

            $item->introtext = JHtml::_('content.prepare', $item->introtext, '', 'mod_lsa_adherents.adherent');

            //new
            if (!$params->get('image')) {
                $item->introtext = preg_replace('/<img[^>]*>/', '', $item->introtext);
            }

            $results = $app->triggerEvent('onContentAfterDisplay', array('com_content.article', &$item, &$params, 1));
            $item->afterDisplayTitle = trim(implode("\n", $results));

            $results = $app->triggerEvent('onContentBeforeDisplay', array('com_content.article', &$item, &$params, 1));
            $item->beforeDisplayContent = trim(implode("\n", $results));
        }

        return $items;
    }

    /**
     *
     * @param type $params
     * @return type 
     */
    public static function getFeatured(&$params) {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();

        // Get an instance of the generic adherents model
        $model = JModelLegacy::getInstance('Adherents', 'AdherentModel', array('ignore_request' => true));

        // Set application parameters in model
        $appParams = JFactory::getApplication()->getParams();
        $model->setState('params', $appParams);

        $model->setState('list.start', 0);
        $model->setState('list.limit', 1);

        $model->setState('filter.published', 1);
        $model->setState('filter.category_id', $params->get('catid', array()));
        $model->setState('filter.featured', 1);

        // Set ordering
        $ordering = $params->get('ordering', 'a.publish_up');
        $model->setState('list.ordering', $ordering);
        if (trim($ordering) == 'rand()') {
            $model->setState('list.direction', '');
        } else {
            $model->setState('list.direction', 'DESC');
        }

        //  Retrieve Content
        $items = $model->getItems();

        foreach ($items as &$item) {

            // Pour mettre le featured en avant
            if($item->featured === '1') {

                $item->readmore = (trim($item->fulltext) != '');
                $item->slug = $item->id . ':' . $item->alias;
                $item->catslug = $item->catid . ':' . $item->category_alias;
            
                $item->introtext = preg_replace('/<img[^>]*>/', '', $item->introtext);
                $item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
                $item->linkText = JText::_('COM_CONTENT_READ_MORE_TITLE');
                break;
            }
        }

        return $items[0];
    }   

}
