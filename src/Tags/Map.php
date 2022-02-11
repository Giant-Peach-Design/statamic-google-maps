<?php

namespace Jezzdk\StatamicGoogleMaps\Tags;

use Illuminate\Support\Str;
use Jezzdk\StatamicGoogleMaps\Helpers\MapHelper;
use Statamic\Facades\Addon;
use Statamic\Tags\Tags;

class Map extends Tags
{
    protected static $handle = 'map';

    protected static $aliases = ['google_map'];

    /**
     * The {{ map }} tag.
     *
     * Usage:
     * {{ map lat="12.1234" lng="52.1234" zoom="14" marker="true" markerLat="12.1243" markerLng="52.1243" type="satellite" showControls="true" }}
     *
     * @return string|array
     */
    public function index()
    {
        if (!env('GOOGLE_MAPS_API_KEY')) {
            return 'Please add a GOOGLE_MAPS_API_KEY to the .env file';
        }

        $lat = $this->params->get('lat');
        $lng = $this->params->get('lng');

        if (!$lat || !$lng) {
            return 'Please set both latitude and longitude';
        }

        return MapHelper::convertToHtml([
            'lat' => $lat,
            'lng' => $lng,
            'zoom' => $this->getZoom(),
            'markerLat' => $this->getMarkerLat(),
            'markerLng' => $this->getMarkerLng(),
            'width' => $this->getWidth(),
            'height' => $this->getHeight(),
            'type' => $this->getType(),
            'icon' => $this->getIcon(),
            'style' => $this->getStyle(),
            'showControls' => $this->getShowControls(),
        ]);
    }

    public function zoom()
    {
        return $this->getZoom();
    }

    public function centerLat()
    {
        return $this->params->get('lat');
    }

    public function centerLng()
    {
        return  $this->params->get('lng');
    }

    public function markerLat()
    {
        return $this->getMarkerLat();
    }

    public function markerLng()
    {
        return $this->getMarkerLng();
    }

    public function type()
    {
        return $this->getType();
    }

    public function icon()
    {
        return $this->getIcon();
    }

    public function style()
    {
        return $this->getStyle();
    }

    public function showControls()
    {
        return $this->showControls();
    }

    protected function getZoom()
    {
        return $this->params->get('zoom', 16);
    }

    protected function getMarkerLat()
    {
        if ($this->params->get('marker', false)) {
            return $this->params->get('lat');
        }

        return $this->params->get('markerLat');
    }

    protected function getMarkerLng()
    {
        if ($this->params->get('marker', false)) {
            return $this->params->get('lng');
        }

        return $this->params->get('markerLng');
    }

    protected function getWidth()
    {
        $width = $this->params->get('width', '100%');

        if (!Str::endsWith($width, '%')) {
            $width .= 'px';
        }

        return $width;
    }

    protected function getHeight()
    {
        $height = $this->params->get('height', '100%');

        if (!Str::endsWith($height, '%')) {
            $height .= 'px';
        }

        return $height;
    }

    protected function getType()
    {
        return $this->params->get('type', 'roadmap');
    }

    protected function getIcon()
    {
        $addon = Addon::get('jezzdk/statamic-google-maps');

        if ($addon->edition() !== 'pro') {
            return null;
        }

        return $this->params->get('icon');
    }

    protected function getStyle()
    {
        $addon = Addon::get('jezzdk/statamic-google-maps');

        if ($addon->edition() !== 'pro') {
            return null;
        }

        return $this->params->get('style');
    }

    protected function getShowControls()
    {
        return $this->params->get('showControls', false);
    }
}
