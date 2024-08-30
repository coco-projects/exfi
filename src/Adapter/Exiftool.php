<?php

    namespace Coco\exfi\Adapter;

    use RuntimeException;

class Exiftool extends AdapterAbstract
{
    protected array $encoding = [];
    protected bool  $numeric  = false;

    public function __construct(string $file)
    {
        $this->initEncoding();
        parent::__construct($file);
    }

    protected function readData(): void
    {
        $encoding = '-charset ';
        foreach ($this->encoding as $key => $value) {
            $encoding .= escapeshellarg($key) . '=' . escapeshellarg($value);
        }

        $bin = realpath(dirname(__DIR__)) . '/bin/exiftool';

        $result = $this->getCliOutput(sprintf('%1$s%3$s -j -a -G1 %5$s -c %4$s %2$s', $bin, escapeshellarg($this->photoPath), $this->numeric ? ' -n' : '', escapeshellarg('%d deg %d\' %.4f"'), $encoding));

        if (!mb_check_encoding($result, "utf-8")) {
            $result = utf8_encode($result);
        }
        if ($data = json_decode($result, true)) {
            $this->rawData = $data[0];
        }
    }


    protected function unitize(): void
    {
        $fullPath = realpath($this->rawData['SourceFile'] ?? "");
        $this->pushUnitizedData(static::FILE_NAME, $this->rawData['System:FileName'] ?? "");
        $this->pushUnitizedData(static::SOURCE_DIRECTORY, dirname($fullPath));
        $this->pushUnitizedData(static::SOURCE_FILE, $fullPath);
        $this->pushUnitizedData(static::FILE_SIZE, $this->rawData['System:FileSize'] ?? "");
        $this->pushUnitizedData(static::IMAGE_WIDTH, $this->rawData['File:ImageWidth'] ?? "");
        $this->pushUnitizedData(static::IMAGE_LENGTH, $this->rawData['File:ImageHeight'] ?? "");
        $this->pushUnitizedData(static::FILE_TYPE, $this->rawData['File:FileType'] ?? "");
        $this->pushUnitizedData(static::FILE_TYPE_EXTENSION, $this->rawData['File:FileTypeExtension'] ?? "");
        $this->pushUnitizedData(static::DATE_TIME_ORIGINAL, $this->rawData['ExifIFD:DateTimeOriginal'] ?? "");
        $this->pushUnitizedData(static::MAKE, $this->rawData['IFD0:Make'] ?? "");
        $this->pushUnitizedData(static::MODEL, $this->rawData['IFD0:Model'] ?? "");
        $this->pushUnitizedData(static::SOFTWARE, $this->rawData['IFD0:Software'] ?? "");
        $this->pushUnitizedData(static::ORIENTATION, $this->rawData['IFD0:Orientation'] ?? "");
        $this->pushUnitizedData(static::X_RESOLUTION, $this->rawData['IFD0:XResolution'] ?? "");
        $this->pushUnitizedData(static::Y_RESOLUTION, $this->rawData['IFD0:YResolution'] ?? "");
        $this->pushUnitizedData(static::RESOLUTION_UNIT, $this->rawData['IFD0:ResolutionUnit'] ?? "");
        $this->pushUnitizedData(static::BYTE_ORDER_MOTOROLA, $this->rawData['File:ExifByteOrder'] ?? "");
        $this->pushUnitizedData(static::BITS_PER_SAMPLE, $this->rawData['IFD0:BitsPerSample'] ?? "");
        $this->pushUnitizedData(static::YCBCR_POSITIONING, $this->rawData['IFD0:YCbCrPositioning'] ?? "");
        $this->pushUnitizedData(static::DEVICE_SETTING_DESCRIPTION, $this->rawData['IFD0:DeviceSettingDescription'] ?? "");

        $this->pushUnitizedData(static::EXPOSURE_TIME, (function () {
            $value = $this->rawData['ExifIFD:ExposureTime'] ?? "";
            if ($value < 0.25001 && $value > 0) {
                $value = sprintf('1/%d', intval(0.5 + 1 / $value));
            } else {
                $value = sprintf('%.1f', $value);
                $value = preg_replace('/.0$/', '', $value);
            }

            return $value;
        })());

        $this->pushUnitizedData(static::FOCAL_LENGTH, (function () {
            $value = $this->rawData['ExifIFD:FocalLength'] ?? "";

            if (str_contains($value, ' ')) {
                $focalLengthParts = explode(' ', $value);
                $value            = reset($focalLengthParts);
            }

            return $value;
        })());

        $this->pushUnitizedData(static::GPS, (function () {

            $value = '';

            $GPSLatitude  = $this->rawData['GPS:GPSLatitude'] ?? "";
            $GPSLongitude = $this->rawData['GPS:GPSLongitude'] ?? "";

            if ($GPSLatitude && $GPSLongitude) {
                $gpsData['lat'] = $this->extractGPSCoordinates($GPSLatitude);
                $gpsData['lon'] = $this->extractGPSCoordinates($GPSLongitude);

                if (count($gpsData) === 2 && $gpsData['lat'] && $gpsData['lon']) {
                    $latitudeRef  = empty($data['GPS:GPSLatitudeRef'][0]) ? 'N' : $data['GPS:GPSLatitudeRef'][0];
                    $longitudeRef = empty($data['GPS:GPSLongitudeRef'][0]) ? 'E' : $data['GPS:GPSLongitudeRef'][0];

                    $gpsLocation = sprintf('%s,%s', (strtoupper($latitudeRef) === 'S' ? -1 : 1) * $gpsData['lat'], (strtoupper($longitudeRef) === 'W' ? -1 : 1) * $gpsData['lon'] ?? "");

                    $value = $gpsLocation;
                }
            }

            return $value;
        })());

        $this->pushUnitizedData(static::GPS_LATITUDE_REF, $this->rawData['GPS:GPSLatitudeRef'] ?? "");
        $this->pushUnitizedData(static::GPS_LONGITUDE_REF, $this->rawData['GPS:GPSLongitudeRef'] ?? "");
        $this->pushUnitizedData(static::GPS_LATITUDE, $this->rawData['GPS:GPSLatitude'] ?? "");
        $this->pushUnitizedData(static::GPS_LONGITUDE, $this->rawData['GPS:GPSLongitude'] ?? "");
        $this->pushUnitizedData(static::F_NUMBER, $this->rawData['ExifIFD:FNumber'] ?? "");
        $this->pushUnitizedData(static::EXPOSURE_PROGRAM, $this->rawData['ExifIFD:ExposureProgram'] ?? "");
        $this->pushUnitizedData(static::ISO_SPEED_RATINGS, $this->rawData['ExifIFD:ISO'] ?? "");
        $this->pushUnitizedData(static::EXIF_VERSION, $this->rawData['ExifIFD:ExifVersion'] ?? "");
        $this->pushUnitizedData(static::COMPONENTS_CONFIGURATION, $this->rawData['ExifIFD:ComponentsConfiguration'] ?? "");
        $this->pushUnitizedData(static::COMPRESSED_BITS_PER_PIXEL, $this->rawData['ExifIFD:CompressedBitsPerPixel'] ?? "");
        $this->pushUnitizedData(static::SHUTTER_SPEED_VALUE, $this->rawData['ExifIFD:ShutterSpeedValue'] ?? "");
        $this->pushUnitizedData(static::APERTURE_VALUE, sprintf('f/%01.1f', $this->rawData['Composite:Aperture'] ?? ""));
        $this->pushUnitizedData(static::MAX_APERTURE_VALUE, sprintf('f/%01.1f', $this->rawData['ExifIFD:MaxApertureValue'] ?? ""));
        $this->pushUnitizedData(static::XMP_AUX_APPROXIMATE_FOCUS_DISTANCE, sprintf('%1$sm', $this->rawData['XMP-aux:ApproximateFocusDistance'] ?? ""));
        $this->pushUnitizedData(static::BRIGHTNESS_VALUE, $this->rawData['ExifIFD:BrightnessValue'] ?? "");
        $this->pushUnitizedData(static::LIGHT_SOURCE, $this->rawData['ExifIFD:LightSource'] ?? "");
        $this->pushUnitizedData(static::FLASH, $this->rawData['ExifIFD:Flash'] ?? "");
        $this->pushUnitizedData(static::MAKER_NOTE, $this->rawData['ExifIFD:MakerNoteUnknownText'] ?? "");
        $this->pushUnitizedData(static::SUB_SEC_TIME, $this->rawData['ExifIFD:SubSecTime'] ?? "");
        $this->pushUnitizedData(static::SUB_SEC_TIME_ORIGINAL, $this->rawData['ExifIFD:SubSecTimeOriginal'] ?? "");
        $this->pushUnitizedData(static::SUB_SEC_TIME_DIGITIZED, $this->rawData['ExifIFD:SubSecTimeDigitized'] ?? "");
        $this->pushUnitizedData(static::FLASH_PIX_VERSION, $this->rawData['ExifIFD:FlashpixVersion'] ?? "");
        $this->pushUnitizedData(static::COLOR_SPACE, $this->rawData['ExifIFD:ColorSpace'] ?? "");
        $this->pushUnitizedData(static::EXIF_IMAGE_WIDTH, $this->rawData['ExifIFD:ExifImageWidth'] ?? "");
        $this->pushUnitizedData(static::EXIF_IMAGE_LENGTH, $this->rawData['ExifIFD:ExifImageHeight'] ?? "");
        $this->pushUnitizedData(static::SENSING_METHOD, $this->rawData['ExifIFD:SensingMethod'] ?? "");
        $this->pushUnitizedData(static::SCENE_TYPE, $this->rawData['ExifIFD:SceneType'] ?? "");
        $this->pushUnitizedData(static::CUSTOM_RENDERED, $this->rawData['ExifIFD:CustomRendered'] ?? "");
        $this->pushUnitizedData(static::EXPOSURE_MODE, $this->rawData['ExifIFD:ExposureMode'] ?? "");
        $this->pushUnitizedData(static::WHITE_BALANCE, $this->rawData['ExifIFD:WhiteBalance'] ?? "");
        $this->pushUnitizedData(static::DIGITAL_ZOOM_RATIO, $this->rawData['ExifIFD:DigitalZoomRatio'] ?? "");
        $this->pushUnitizedData(static::FOCAL_LENGTH_IN_35MM_FILM, $this->rawData['ExifIFD:FocalLengthIn35mmFormat'] ?? "");
        $this->pushUnitizedData(static::SCENE_CAPTURE_TYPE, $this->rawData['ExifIFD:SceneCaptureType'] ?? "");
        $this->pushUnitizedData(static::GAIN_CONTROL, $this->rawData['ExifIFD:GainControl'] ?? "");
        $this->pushUnitizedData(static::CONTRAST, $this->rawData['ExifIFD:Contrast'] ?? "");
        $this->pushUnitizedData(static::SATURATION, $this->rawData['ExifIFD:Saturation'] ?? "");
        $this->pushUnitizedData(static::SHARPNESS, $this->rawData['ExifIFD:Sharpness'] ?? "");
        $this->pushUnitizedData(static::SUBJECT_DISTANCE_RANGE, $this->rawData['ExifIFD:SubjectDistanceRange'] ?? "");
        $this->pushUnitizedData(static::INTEROP_INDEX, $this->rawData['InteropIFD:InteropIndex'] ?? "");
        $this->pushUnitizedData(static::INTEROP_VERSION, $this->rawData['InteropIFD:InteropVersion'] ?? "");
        $this->pushUnitizedData(static::COMPOSITE_IMAGE_SIZE, $this->rawData['Composite:ImageSize'] ?? "");
        $this->pushUnitizedData(static::COMPOSITE_MEGAPIXELS, $this->rawData['Composite:Megapixels'] ?? "");
        $this->pushUnitizedData(static::COMPOSITE_SCALE_FACTOR_35EFL, $this->rawData['Composite:ScaleFactor35efl'] ?? "");
        $this->pushUnitizedData(static::COMPOSITE_SHUTTER_SPEED, $this->rawData['Composite:ShutterSpeed'] ?? "");
        $this->pushUnitizedData(static::COMPOSITE_SUB_SEC_CREATE_DATE, $this->rawData['Composite:SubSecCreateDate'] ?? "");
        $this->pushUnitizedData(static::COMPOSITE_SUB_SEC_DATE_TIME_ORIGINAL, $this->rawData['Composite:SubSecDateTimeOriginal'] ?? "");
        $this->pushUnitizedData(static::COMPOSITE_SUB_SEC_MODIFY_DATE, $this->rawData['Composite:SubSecModifyDate'] ?? "");
        $this->pushUnitizedData(static::COMPOSITE_CIRCLE_OF_CONFUSION, $this->rawData['Composite:CircleOfConfusion'] ?? "");
        $this->pushUnitizedData(static::COMPOSITE_FOV, $this->rawData['Composite:FOV'] ?? "");
        $this->pushUnitizedData(static::COMPOSITE_FOCAL_LENGTH_35EFL, $this->rawData['Composite:FocalLength35efl'] ?? "");
        $this->pushUnitizedData(static::COMPOSITE_HYPERFOCAL_DISTANCE, $this->rawData['Composite:HyperfocalDistance'] ?? "");
        $this->pushUnitizedData(static::COMPOSITE_LIGHT_VALUE, $this->rawData['Composite:LightValue'] ?? "");
    }

    protected function getCliOutput($command): bool|string
    {
        $descriptorspec = [
            0 => [
                'pipe',
                'r',
            ],
            1 => [
                'pipe',
                'w',
            ],
            2 => [
                'pipe',
                'a',
            ],
        ];

        $process = proc_open($command, $descriptorspec, $pipes);

        if (!is_resource($process)) {
            throw new RuntimeException('Could not open a resource to the exiftool binary');
        }

        $result = stream_get_contents($pipes[1] ?? "");
        fclose($pipes[0] ?? "");
        fclose($pipes[1] ?? "");
        fclose($pipes[2] ?? "");

        proc_close($process);

        return $result;
    }


    protected function initEncoding(): void
    {
        $possible_keys   = [
            "exif",
            "iptc",
            "id3",
            "photoshop",
            "quicktime",
        ];
        $possible_values = [
            "UTF8",
            "cp65001",
            "UTF-8",
            "Thai",
            "cp874",
            "Latin",
            "cp1252",
            "Latin1",
            "MacRoman",
            "cp10000",
            "Mac",
            "Roman",
            "Latin2",
            "cp1250",
            "MacLatin2",
            "cp10029",
            "Cyrillic",
            "cp1251",
            "Russian",
            "MacCyrillic",
            "cp10007",
            "Greek",
            "cp1253",
            "MacGreek",
            "cp10006",
            "Turkish",
            "cp1254",
            "MacTurkish",
            "cp10081",
            "Hebrew",
            "cp1255",
            "MacRomanian",
            "cp10010",
            "Arabic",
            "cp1256",
            "MacIceland",
            "cp10079",
            "Baltic",
            "cp1257",
            "MacCroatian",
            "cp10082",
            "Vietnam",
            "cp1258",
        ];

        foreach (["exif" => "UTF-8"] as $type => $encoding_) {
            if (in_array($type, $possible_keys) && in_array($encoding_, $possible_values)) {
                $this->encoding[$type] = $encoding_;
            }
        }
    }


    protected function extractGPSCoordinates($coordinates): float|int
    {
        if (!preg_match('!^([0-9.]+) deg ([0-9.]+)\' ([0-9.]+)"!', $coordinates, $matches)) {
            return 0;
        }

        return floatval($matches[1]) + (floatval($matches[2]) / 60) + (floatval($matches[3]) / 3600);
    }
}
