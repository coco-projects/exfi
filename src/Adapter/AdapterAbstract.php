<?php

    namespace Coco\exfi\Adapter;

abstract class AdapterAbstract
{

    const SOURCE_DIRECTORY = 'SourceDirectory';
    const SOURCE_FILE      = 'SourceFile';
    /*---------------------------------------------------------*/
    const FILE_NAME                  = 'FileName';
    const FILE_SIZE                  = 'FileSize';
    const FILE_TYPE                  = 'FileType';
    const MIME_TYPE                  = 'MimeType';
    const FILE_TYPE_EXTENSION        = 'File:FileTypeExtension';
    const IS_COLOR                   = 'IsColor';
    const BYTE_ORDER_MOTOROLA        = 'ByteOrderMotorola';
    const IMAGE_WIDTH                = 'ImageWidth';
    const IMAGE_LENGTH               = 'ImageLength';
    const BITS_PER_SAMPLE            = 'BitsPerSample';
    const MAKE                       = 'Make';
    const MODEL                      = 'Model';
    const ORIENTATION                = 'Orientation';
    const X_RESOLUTION               = 'XResolution';
    const Y_RESOLUTION               = 'YResolution';
    const RESOLUTION_UNIT            = 'ResolutionUnit';
    const SOFTWARE                   = 'Software';
    const DATE_TIME                  = 'DateTime';
    const YCBCR_POSITIONING          = 'YCbCrPositioning';
    const EXIF_IFD_POINTER           = 'Exif_IFD_Pointer';
    const DEVICE_SETTING_DESCRIPTION = 'DeviceSettingDescription';
    const EXPOSURE_TIME              = 'ExposureTime';
    const F_NUMBER                   = 'FNumber';
    const EXPOSURE_PROGRAM           = 'ExposureProgram';
    const ISO_SPEED_RATINGS          = 'ISOSpeedRatings';
    const EXIF_VERSION               = 'ExifVersion';
    const DATE_TIME_ORIGINAL         = 'DateTimeOriginal';
    const DATE_TIME_DIGITIZED        = 'DateTimeDigitized';
    const COMPONENTS_CONFIGURATION   = 'ComponentsConfiguration';
    const COMPRESSED_BITS_PER_PIXEL  = 'CompressedBitsPerPixel';
    const SHUTTER_SPEED_VALUE        = 'ShutterSpeedValue';
    const APERTURE_VALUE             = 'ApertureValue';
    const BRIGHTNESS_VALUE           = 'BrightnessValue';
    const EXPOSURE_BIAS_VALUE        = 'ExposureBiasValue';
    const MAX_APERTURE_VALUE         = 'MaxApertureValue';
    const METERING_MODE              = 'MeteringMode';
    const LIGHT_SOURCE               = 'LightSource';
    const FLASH                      = 'Flash';
    const FOCAL_LENGTH               = 'FocalLength';
    const MAKER_NOTE                 = 'MakerNote';
    const SUB_SEC_TIME               = 'SubSecTime';
    const SUB_SEC_TIME_ORIGINAL      = 'SubSecTimeOriginal';
    const SUB_SEC_TIME_DIGITIZED     = 'SubSecTimeDigitized';
    const FLASH_PIX_VERSION          = 'FlashPixVersion';
    const COLOR_SPACE                = 'ColorSpace';
    const EXIF_IMAGE_WIDTH           = 'ExifImageWidth';
    const EXIF_IMAGE_LENGTH          = 'ExifImageLength';
    const INTEROPERABILITY_OFFSET    = 'InteroperabilityOffset';
    const SENSING_METHOD             = 'SensingMethod';
    const SCENE_TYPE                 = 'SceneType';
    const CUSTOM_RENDERED            = 'CustomRendered';
    const EXPOSURE_MODE              = 'ExposureMode';
    const WHITE_BALANCE              = 'WhiteBalance';
    const DIGITAL_ZOOM_RATIO         = 'DigitalZoomRatio';
    const FOCAL_LENGTH_IN_35MM_FILM  = 'FocalLengthIn35mmFilm';
    const SCENE_CAPTURE_TYPE         = 'SceneCaptureType';
    const GAIN_CONTROL               = 'GainControl';
    const CONTRAST                   = 'Contrast';
    const SATURATION                 = 'Saturation';
    const SHARPNESS                  = 'Sharpness';
    const SUBJECT_DISTANCE_RANGE     = 'SubjectDistanceRange';
    const INTEROP_INDEX              = 'InterOperabilityIndex';
    const INTEROP_VERSION            = 'InterOperabilityVersion';
    const IPTC                       = 'iptc';
    /*---------------------------------------------------------*/
    const SYSTEM_FILE_MODIFY_DATE              = 'System:FileModifyDate';
    const SYSTEM_FILE_ACCESS_DATE              = 'System:FileAccessDate';
    const SYSTEM_FILE_INODE_CHANGE_DATE        = 'System:FileInodeChangeDate';
    const SYSTEM_FILE_PERMISSIONS              = 'System:FilePermissions';
    const ENCODING_PROCESS                     = 'File:EncodingProcess';
    const YCBCR_SUBSAMPLING                    = 'File:YCbCrSubSampling';
    const EXIF_EXPOSURE_COMPENSATION           = 'ExifIFD:ExposureCompensation';
    const COLOR_COMPONENTS                     = 'File:ColorComponents';
    const COMPOSITE_APERTURE                   = 'Composite:Aperture';
    const COMPOSITE_IMAGE_SIZE                 = 'Composite:ImageSize';
    const COMPOSITE_MEGAPIXELS                 = 'Composite:Megapixels';
    const COMPOSITE_SCALE_FACTOR_35EFL         = 'Composite:ScaleFactor35efl';
    const COMPOSITE_SHUTTER_SPEED              = 'Composite:ShutterSpeed';
    const COMPOSITE_SUB_SEC_CREATE_DATE        = 'Composite:SubSecCreateDate';
    const COMPOSITE_SUB_SEC_DATE_TIME_ORIGINAL = 'Composite:SubSecDateTimeOriginal';
    const COMPOSITE_SUB_SEC_MODIFY_DATE        = 'Composite:SubSecModifyDate';
    const COMPOSITE_CIRCLE_OF_CONFUSION        = 'Composite:CircleOfConfusion';
    const COMPOSITE_FOV                        = 'Composite:FOV';
    const COMPOSITE_FOCAL_LENGTH_35EFL         = 'Composite:FocalLength35efl';
    const COMPOSITE_HYPERFOCAL_DISTANCE        = 'Composite:HyperfocalDistance';
    const COMPOSITE_LIGHT_VALUE                = 'Composite:LightValue';
    const XMP_AUX_APPROXIMATE_FOCUS_DISTANCE   = 'XMP-aux:ApproximateFocusDistance';
    const GPS_LATITUDE_REF                     = 'GPS:GPSLatitudeRef';
    const GPS_LONGITUDE_REF                    = 'GPS:GPSLongitudeRef';
    const GPS_LATITUDE                         = 'GPS:GPSLatitude';
    const GPS_LONGITUDE                        = 'GPS:GPSLongitude';
    const GPS                                  = 'GPS';
    protected array $dataMap = [
        self::FILE_NAME                            => '源文档名',
        self::SOURCE_FILE                          => '源文档路径',
        self::SOURCE_DIRECTORY                     => '源文档夹',
        self::ENCODING_PROCESS                     => '编码过程',
        self::COLOR_COMPONENTS                     => '颜色信道',
        self::YCBCR_SUBSAMPLING                    => 'YCbCr子采样',
        self::FILE_SIZE                            => '文档大小',
        self::MIME_TYPE                            => '文档类型',
        self::SYSTEM_FILE_MODIFY_DATE              => '文档修改日期',
        self::SYSTEM_FILE_ACCESS_DATE              => '文档访问日期',
        self::SYSTEM_FILE_INODE_CHANGE_DATE        => '文档inode更改日期',
        self::SYSTEM_FILE_PERMISSIONS              => '文档权限',
        self::FILE_TYPE                            => '文档类型',
        self::FILE_TYPE_EXTENSION                  => '文档扩展名',
        self::EXIF_EXPOSURE_COMPENSATION           => '曝光补偿',
        self::IS_COLOR                             => '是否为彩色图像',
        self::BYTE_ORDER_MOTOROLA                  => '字节顺序 (摩托罗拉格式)',
        self::IMAGE_WIDTH                          => '图像宽度',
        self::IMAGE_LENGTH                         => '图像高度',
        self::BITS_PER_SAMPLE                      => '每个样本的位数',
        self::MAKE                                 => '品牌',
        self::MODEL                                => '型号',
        self::ORIENTATION                          => '拍摄方向',
        self::X_RESOLUTION                         => '水平分辨率',
        self::Y_RESOLUTION                         => '垂直分辨率',
        self::RESOLUTION_UNIT                      => '分辨率单位',
        self::SOFTWARE                             => '软件',
        self::DATE_TIME                            => '拍摄时间',
        self::YCBCR_POSITIONING                    => 'YCbCr定位',
        self::EXIF_IFD_POINTER                     => 'Exif IFD 指针',
        self::DEVICE_SETTING_DESCRIPTION           => '设备设置描述',
        self::EXPOSURE_TIME                        => '曝光时间',
        self::F_NUMBER                             => '光圈值',
        self::EXPOSURE_PROGRAM                     => '曝光程序',
        self::ISO_SPEED_RATINGS                    => 'ISO速度等级',
        self::EXIF_VERSION                         => 'EXIF版本',
        self::DATE_TIME_ORIGINAL                   => '原始拍摄时间',
        self::DATE_TIME_DIGITIZED                  => '数字化时间',
        self::COMPONENTS_CONFIGURATION             => '组件配置',
        self::COMPRESSED_BITS_PER_PIXEL            => '每像素压缩位数',
        self::SHUTTER_SPEED_VALUE                  => '快门速度',
        self::APERTURE_VALUE                       => '光圈值',
        self::BRIGHTNESS_VALUE                     => '亮度值',
        self::EXPOSURE_BIAS_VALUE                  => '曝光偏移值',
        self::MAX_APERTURE_VALUE                   => '最大光圈值',
        self::METERING_MODE                        => '测光模式',
        self::LIGHT_SOURCE                         => '光源类型',
        self::FLASH                                => '闪光灯',
        self::FOCAL_LENGTH                         => '焦距',
        self::MAKER_NOTE                           => '制造商备注',
        self::SUB_SEC_TIME                         => '亚秒时间',
        self::SUB_SEC_TIME_ORIGINAL                => '原始亚秒时间',
        self::SUB_SEC_TIME_DIGITIZED               => '数字化亚秒时间',
        self::FLASH_PIX_VERSION                    => 'FlashPix版本',
        self::COLOR_SPACE                          => '色彩空间',
        self::EXIF_IMAGE_WIDTH                     => 'EXIF图像宽度',
        self::EXIF_IMAGE_LENGTH                    => 'EXIF图像高度',
        self::INTEROPERABILITY_OFFSET              => '互操作性偏移量',
        self::SENSING_METHOD                       => '感应方式',
        self::SCENE_TYPE                           => '场景类型',
        self::CUSTOM_RENDERED                      => '自定义渲染',
        self::EXPOSURE_MODE                        => '曝光模式',
        self::WHITE_BALANCE                        => '白平衡',
        self::DIGITAL_ZOOM_RATIO                   => '数码变焦比例',
        self::FOCAL_LENGTH_IN_35MM_FILM            => '35mm格式下的等效焦距',
        self::SCENE_CAPTURE_TYPE                   => '场景捕捉类型',
        self::GAIN_CONTROL                         => '增益控制',
        self::CONTRAST                             => '对比度',
        self::SATURATION                           => '饱和度',
        self::SHARPNESS                            => '锐度',
        self::SUBJECT_DISTANCE_RANGE               => '被摄体距离范围',
        self::INTEROP_INDEX                        => '互操作性索引',
        self::INTEROP_VERSION                      => '互操作性版本',
        self::COMPOSITE_APERTURE                   => '复合光圈值',
        self::COMPOSITE_IMAGE_SIZE                 => '复合图像尺寸',
        self::COMPOSITE_MEGAPIXELS                 => '复合百万像素',
        self::COMPOSITE_SCALE_FACTOR_35EFL         => '复合35mm等效焦距比例',
        self::COMPOSITE_SHUTTER_SPEED              => '复合快门速度',
        self::COMPOSITE_SUB_SEC_CREATE_DATE        => '复合亚秒创建日期',
        self::COMPOSITE_SUB_SEC_DATE_TIME_ORIGINAL => '复合亚秒原始日期时间',
        self::COMPOSITE_SUB_SEC_MODIFY_DATE        => '复合亚秒修改日期',
        self::COMPOSITE_CIRCLE_OF_CONFUSION        => '复合模糊圆',
        self::COMPOSITE_FOV                        => '复合视野',
        self::COMPOSITE_FOCAL_LENGTH_35EFL         => '复合35mm等效焦距',
        self::COMPOSITE_HYPERFOCAL_DISTANCE        => '复合超焦距',
        self::COMPOSITE_LIGHT_VALUE                => '复合光值',
        self::IPTC                                 => 'IPTC',
        self::XMP_AUX_APPROXIMATE_FOCUS_DISTANCE   => 'XMP_AUX近似焦距',
        self::GPS                                  => 'GPS',
        self::GPS_LATITUDE_REF                     => 'GPS纬度参考',
        self::GPS_LONGITUDE_REF                    => 'GPS经度参考',
        self::GPS_LATITUDE                         => 'GPS纬度',
        self::GPS_LONGITUDE                        => 'GPS经度',
    ];


    protected array $rawData      = [];
    protected array $unitizedData = [];

    public function __construct(protected string $photoPath)
    {
        $this->readData();
        $this->unitize();
    }

    public function getPhotoPath(): string
    {
        return $this->photoPath;
    }

    public function getRawData(): array
    {
        return $this->rawData;
    }

    public function pushUnitizedData(string $key, $value): static
    {
        $this->unitizedData[$key] = [
            'value' => $value,
            'title' => $this->dataMap[$key],
        ];

        return $this;
    }

    public function getUnitizedData(): array
    {
        return $this->unitizedData;
    }

    abstract protected function unitize();

    abstract protected function readData();
}
