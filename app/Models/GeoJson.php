<?php

namespace App\Models;

use GeoJson\Feature\Feature;
use GeoJson\Feature\FeatureCollection;
use GeoJson\Geometry\LineString;
use GeoJson\Geometry\Point;

/**
 * Return different points/features in GeoJSON format
 * https://tools.ietf.org/html/rfc7946
 */
class GeoJson
{
    /**
     * @var int
     */
    protected $counter;

    /**
     * @var array [lon, lat] pairs
     */
    protected $line_coords = [];

    /**
     * @var Feature[]
     */
    protected $point_coords = [];

    /**
     * @param array $attrs Attributes of the Feature
     */
    public function addPoint($lat, $lon, array $attrs)
    {
        // Validate that lat and lon are valid numeric values
        if ($lat === null || $lon === null || !is_numeric($lat) || !is_numeric($lon)) {
            // Log invalid coordinates for debugging if needed
            \Log::debug('Invalid coordinates skipped in GeoJson::addPoint', [
                'lat' => $lat,
                'lon' => $lon,
                'lat_type' => gettype($lat),
                'lon_type' => gettype($lon)
            ]);
            return; // Skip invalid coordinates
        }

        // Convert to float and validate range
        $lat = (float) $lat;
        $lon = (float) $lon;

        // Validate latitude range (-90 to 90)
        if ($lat < -90 || $lat > 90) {
            \Log::debug('Invalid latitude range skipped in GeoJson::addPoint', ['lat' => $lat]);
            return; // Skip invalid latitude
        }

        // Validate longitude range (-180 to 180)
        if ($lon < -180 || $lon > 180) {
            \Log::debug('Invalid longitude range skipped in GeoJson::addPoint', ['lon' => $lon]);
            return; // Skip invalid longitude
        }

        $point = [$lon, $lat];
        $this->line_coords[] = [$lon, $lat];

        if (array_key_exists('alt', $attrs) && is_numeric($attrs['alt'])) {
            $point[] = (float) $attrs['alt'];
        }

        $this->point_coords[] = new Feature(new Point($point), $attrs);
        $this->counter++;
    }

    /**
     * Get the FeatureCollection for the line
     */
    public function getLine(): FeatureCollection
    {
        if ($this->line_coords === [] || \count($this->line_coords) < 2) {
            return new FeatureCollection([]);
        }

        return new FeatureCollection([
            new Feature(new LineString($this->line_coords)),
        ]);
    }

    /**
     * Get the feature collection of all the points
     */
    public function getPoints(): FeatureCollection
    {
        return new FeatureCollection($this->point_coords);
    }
}
