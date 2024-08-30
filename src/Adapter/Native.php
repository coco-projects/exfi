<?php

    namespace Coco\exfi\Adapter;

class Native extends AdapterAbstract
{
    protected array $iptcMapping = [
        'title'     => '2#005',
        'keywords'  => '2#025',
        'copyright' => '2#116',
        'caption'   => '2#120',
        'headline'  => '2#105',
        'credit'    => '2#110',
        'source'    => '2#115',
        'jobtitle'  => '2#085',
    ];

    protected function readData(): void
    {
        $data = @exif_read_data($this->photoPath, null, true);

        $xmpData = $this->getIptcData($this->photoPath);

        $this->rawData = array_merge($data, ['iptc' => $xmpData]);
    }

    protected function unitize(): void
    {
        $this->pushUnitizedData(static::F_NUMBER, $this->rawData['COMPUTED']['ApertureFNumber'] ?? "");
        $this->pushUnitizedData(static::IS_COLOR, $this->rawData['COMPUTED']['IsColor'] ?? "");
        $this->pushUnitizedData(static::EXPOSURE_BIAS_VALUE, $this->rawData['EXIF']['ExposureBiasValue'] ?? "");
        $this->pushUnitizedData(static::DATE_TIME, $this->rawData['IFD0']['DateTime'] ?? "");
        $this->pushUnitizedData(static::DATE_TIME_DIGITIZED, $this->rawData['EXIF']['DateTimeDigitized'] ?? "");
        $this->pushUnitizedData(static::METERING_MODE, $this->rawData['EXIF']['MeteringMode'] ?? "");
        $this->pushUnitizedData(static::INTEROPERABILITY_OFFSET, $this->rawData['EXIF']['InteroperabilityOffset'] ?? "");
        $this->pushUnitizedData(static::EXIF_IFD_POINTER, $this->rawData['IFD0']['Exif_IFD_Pointer'] ?? "");
        $this->pushUnitizedData(static::IPTC, json_encode($this->rawData['iptc'], 256));

        $this->pushUnitizedData(static::GPS, (function () {

            $value = '';

            $GPSLatitude  = $this->rawData['GPSLatitude'] ?? "";
            $GPSLongitude = $this->rawData['GPSLongitude'] ?? "";

            if ($GPSLatitude && $GPSLongitude) {
                $gpsData['lat'] = $this->extractGPSCoordinate($GPSLatitude);
                $gpsData['lon'] = $this->extractGPSCoordinate($GPSLongitude);

                if (count($gpsData) === 2 && $gpsData['lat'] && $gpsData['lon']) {
                    $latitudeRef  = empty($data['GPSLatitudeRef'][0]) ? 'N' : $data['GPSLatitudeRef'][0];
                    $longitudeRef = empty($data['GPSLongitudeRef'][0]) ? 'E' : $data['GPSLongitudeRef'][0];

                    $gpsLocation = sprintf('%s,%s', (strtoupper($latitudeRef) === 'S' ? -1 : 1) * $gpsData['lat'], (strtoupper($longitudeRef) === 'W' ? -1 : 1) * $gpsData['lon'] ?? "");

                    $value = $gpsLocation;
                }
            }

            return $value;
        })());
    }

    protected function getIptcData($file): array
    {
        getimagesize($file, $info);

        $arrData = [];
        if (isset($info['APP13'])) {
            $iptc = iptcparse($info['APP13']);

            foreach ($this->iptcMapping as $name => $field) {
                if (!isset($iptc[$field])) {
                    continue;
                }

                if (count($iptc[$field]) === 1) {
                    $arrData[$name] = reset($iptc[$field]);
                } else {
                    $arrData[$name] = $iptc[$field];
                }
            }
        }

        return $arrData;
    }

    protected function extractGPSCoordinate($components)
    {
        if (!is_array($components)) {
            $components = [$components];
        }
        $components = array_map([
            $this,
            'normalizeComponent',
        ], $components);

        if (count($components) > 2) {
            return floatval($components[0]) + (floatval($components[1]) / 60) + (floatval($components[2]) / 3600);
        }

        return reset($components);
    }
}
