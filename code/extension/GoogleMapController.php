<?php

/**
 * Inject our map data into the Content Controller
 * 
 */
class GoogleMapController extends Extension {
    
    public function onBeforeInit() {
        Requirements::themedCSS('GoogleMaps', 'googlemaps');
        
        if(
            $this->owner->Maps()->exists()
            && $this->owner->ShowMap
            && !$this->owner->StaticMap
        ) {
            $config = SiteConfig::current_site_config();
            $key = ($config->APIKey) ? "&key={$config->APIKey}" : '';
            
            Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
            Requirements::javascript("http://maps.googleapis.com/maps/api/js?sensor=false" . $key);
            Requirements::javascript('googlemaps/javascript/gmap3.min.js');
        }
    }
    
    public function onAfterInit() {
        if(
            $this->owner->Maps()->exists()
            && $this->owner->ShowMap
            && !$this->owner->StaticMap
        ) {
            foreach($this->owner->Maps() as $map) {
                $vars = array(
                    'MapID'         => "google-map-dynamic-{$map->ID}",
                    'Content'       => $map->Content,
                    'Address'       => ($map->Address) ? str_replace('/n', ',', $map->Address) . ',' . $map->PostCode : 'false',
                    'Latitude'      => ($map->Latitude) ? $map->Latitude : 'false',
                    'Longitude'     => ($map->Longitude) ? $map->Longitude : 'false',
                    'Zoom'          => $map->ZoomLevel
                );
            
                Requirements::javascriptTemplate(
                    'googlemaps/javascript/GoogleMap.js',
                    $vars
                );
            }
        }
    }

    public function GoogleMaps() {
        if($this->owner->Maps()->exists() && $this->owner->ShowMap) {
            $config = SiteConfig::current_site_config();
            $vars = array(
                'Maps' => $this->owner->Maps()
            );
        
            return $this->owner->renderWith('GoogleMaps',$vars);
        } else
            return false;
    }
}
