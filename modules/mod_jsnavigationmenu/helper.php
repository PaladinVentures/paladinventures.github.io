<?php /*
* @package Js Menu
* @copyright (C) 2014 by JoomlaStars - All rights reserved!
* @license GNU/GPL, see LICENSE.php
*/
?>
<?php
defined('_JEXEC') or die('Restricted access');
class modJSNavigationMenuHelper {

    var $name = null;
    
    var $params = null;
    
    var $path = null;

    function render(&$paramsa) {
    
        $this->params = $paramsa;
        
        if (is_null($this->name)) {
        
            $this->name = $this->params->get('menu');
            
        }
		$this->color = $this->params->get('color');
		
        echo ' 
	
           <ul id="navigation-menu" class="clearfix">';
               
			  
        
			        $this->ShowMenu();
        
		      
			   
                echo'</ul>
             ';
    }
    function ShowMenu() {
    	
    	$jsuser 	= JFactory::getUser();
		$jsapp 	= JFactory::getApplication();
        $jsnavigationmenu 	= $jsapp->getMenu();        
        
		$end = $this->params->get('endLevel',0);
		
        //get menu items        
        $rows 	= $jsnavigationmenu->getItems('menutype', $this->name);
		
        $jschild = array();
		
        foreach ($rows as $v) {
        	if($end && $v->level > $end){
        		continue;
        	}
            $pt = $v->parent_id;
            $list = @$jschild[$pt] ? $jschild[$pt] : array();            
            array_push($list, $v);            
            $jschild[$pt] = $list;            
        }
		
        $this->path = $this->getActive();
        
        $this->mosRecurseListMenu(1, 0, $jschild);
        
        return true;
    }

    function getActive() {
    
        $jsapp	= JFactory::getApplication();
		$jsnavigationmenu	= $jsapp->getMenu();
		$active	= $jsnavigationmenu->getActive();
		$active_id = isset($active) ? $active->id : $jsnavigationmenu->getDefault()->id;
		$path	= isset($active) ? $active->tree : array();
		
		return $path;
    }
    
    /**
     
     * Utility function for writing a menu link
     
     */
     
    function mosGetMenuLink($item, $level = 0, &$params, $havechild = null) {
    
        $jsapp 	= JFactory::getApplication();
        $jsnavigationmenu 	= $jsapp->getMenu();
        
		if(!isset($item->flink)) {
		
			$item->flink = $item->link;
		
	        switch ($item->type) {
	        	
						case 'separator':
							$item->browserNav = 3;
							break;
	
						case 'url':
							if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false)) {
								// If this is an internal Joomla link, ensure the Itemid is set.
								$item->flink = $item->link.'&Itemid='.$item->id;
							}
							break;
	
						case 'alias':
							// If this is an alias use the item id stored in the parameters to make the link.
							$item->flink = 'index.php?Itemid='.$item->params->get('aliasoptions');
							break;
	
						default:
							$router = JSite::getRouter();
							if ($router->getMode() == JROUTER_MODE_SEF) {
								$item->flink = 'index.php?Itemid='.$item->id;
							}
							else {
								$item->flink .= '&Itemid='.$item->id;
							}
							break;
			}
	        
	        if (strcasecmp(substr($item->flink, 0, 4), 'http') && (strpos($item->flink, 'index.php?') !== false)) {
				$item->flink = JRoute::_($item->flink, true, $item->params->get('secure'));
			} else {
				$item->flink = JRoute::_($item->flink);
			}
	
	        // get image if selected
			$item->title = htmlspecialchars($item->title);
			$item->anchor_css = htmlspecialchars($item->params->get('menu-anchor_css', ''));
			$item->anchor_title = htmlspecialchars($item->params->get('menu-anchor_title', ''));
			$item->menu_image = $item->params->get('menu_image', '') ? htmlspecialchars($item->params->get('menu_image', '')) : '';
		
		}
		
		// Note. It is important to remove spaces between elements.
		$anchor_title = $item->anchor_title ? 'title="'.$item->anchor_title.'" ' : '';
		if ($item->menu_image) {
				$item->params->get('menu_text', 1 ) ? 
				$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" /><span class="image-title">'.$item->title.'</span> ' :
				$linktype = '&nbsp;<img src="'.$item->menu_image.'" alt="'.$item->title.'" />&nbsp;';
		} 
		else { 
			$linktype = $item->title;
		}
		
		// Add classes
        $classplus = '';        
        if ($item->type == 'alias' && in_array($item->params->get('aliasoptions'),$this->path) || in_array($item->id, $this->path)) {
            $classplus = '';
        }
		$classplus .= $item->anchor_css;
		$class = 'class="'.$classplus.'"';;
        if ($level == 0) $class = 'class="scroll'.(trim($classplus) ? ' '.trim($classplus) : '').'"';            
        if ($havechild && $level != 0) {        
            if ( empty($classplus))
                $class = 'class="js-more"';                
            else
                $class = 'class="js-more-'.$classplus.'"';                
        }
        
		$spanclass = '';
        if ($havechild && $level == 0) {        
            $spanclass = 'class="js-drop" ';            
        }
        if ($level == 0) {
            $linktype = '<span '.$spanclass.'>'.$linktype.'</span>';
        }
		
        $link = '';
        switch ($item->browserNav) {
        
            // cases are slightly different
            default:
            case 0:            
                // same window
				$link = '<a href="'.$item->flink.'" '.$class.' '.$anchor_title.'>'.$linktype.'</a>';
                break;
                
			case 1:
				// _blank
				$link = '<a href="'.$item->flink.'" '.$class.' '.$anchor_title.' target="_blank">'.$linktype.'</a>';
				break;
				
            case 2:
            	// open in a popup window
                $attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550';
				$link = '<a href="'.$item->flink.'" onclick="window.open(this.href,\'targetWindow\',\''.$attribs.'\');return false;" '.$class.' '.$anchor_title.'>'.$linktype.'</a>';
                break;
                
            case 3:
                // don't link it
                $link = '<a '.$class.' '.$anchor_title.'>'.$linktype.'</a>';
                break;
        }

        return $link;
        
    }
     
    function mosRecurseListMenu($id, $level, &$jschild) {
    
        $jsapp 	= JFactory::getApplication();
        $jsnavigationmenu 	= $jsapp->getMenu();
        
        if (@$jschild[$id]) {
        
            $elements = count($jschild[$id]);            
            $counter = 0;
            
            foreach ($jschild[$id] as $row) {
            
                $counter++;                
                $separator = ($row->type == 'separator' ? true : false);
                @$havechild = is_array($jschild[$row->id]);
                
                $classname = "";                
                if ($level == 0) {
                    $classname .= "";
                }				
				$classname .= "itemid".$row->id." ";                
                if ($counter == 1) {                
                    $classname .= "first ";                    
                } else if ($counter == $elements) {                
                    $classname .= "last ";                    
                }                
                if ($row->type == 'alias' && in_array($row->params->get('aliasoptions'),$this->path) || in_array($row->id, $this->path)) {
                    $classname .= "active ";
                }				
                if ($separator) {
                    $classname .= "separator";
                }
                
				if ($havechild) {
                    $classname .= " dropdown-toggle nav-toggle";
                }
				
                $class = "";
                
                if (!empty($classname)) {                
                    $class = " class=\"".trim($classname)."\"";
                }

                if ($havechild) {
                
                    echo "<li".$class.">".$this->mosGetMenuLink($row, $level, $this->params, 1)."\n";
                    
                    if ($level == 0) {
                    	
                        echo "<ul class=\"dropdown-menu\">\n";
						
                        
                        $this->mosRecurseListMenu($row->id, $level + 1, $jschild);
                        
						
                        echo "</ul>\n";
                        
                    } else {
                    
                        echo "<ul class=\"dropdown-menu\">\n";
                       
						
                        $this->mosRecurseListMenu($row->id, $level + 1, $jschild);
                        
						
                        echo "</ul>\n";

                    }
                    
                    echo "</li>\n";
                    
                } else {
                
                    echo "<li".$class.">".$this->mosGetMenuLink($row, $level, $this->params)."</li>\n";
                    
                }
                
            }
            
        }
        
    }
}
?>