<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\QrCodes;

use BaconQrCode\Renderer\Eye\EyeInterface;
use BaconQrCode\Renderer\Eye\SquareEye;
use BaconQrCode\Renderer\Path\Path;
use SimpleSoftwareIO\QrCode\Singleton;

final class EyeCombiner implements EyeInterface, Singleton
{
    /**
     * @var self|null
     */
    private static $instance;

    private static $inner_eyes = [
        'square' => SquareEye::class,
        'dot' => CircleEye::class,
        'rounded' => RoundedEye::class,
        'diamond' => DiamondEye::class,
        'flower' => FlowerEye::class,
        'leaf' => LeafEye::class,
    ];

    private static $outer_eyes = [
        'square' => SquareEye::class,
        'circle' => CircleEye::class,
        'rounded' => RoundedEye::class,
        'flower' => FlowerEye::class,
        'leaf' => LeafEye::class,
    ];

    private static $outer_eye;

    private static $inner_eye;

    private function __construct()
    {
    }

    public static function instance($inner_eye = null, $outer_eye = null) : self
    {
        self::$inner_eye = array_key_exists($inner_eye, self::$inner_eyes) ? self::$inner_eyes[$inner_eye] : self::$inner_eyes['square'];

        self::$outer_eye = array_key_exists($outer_eye, self::$outer_eyes) ? self::$outer_eyes[$outer_eye] : self::$outer_eyes['square'];

        return self::$instance ?: self::$instance = new self();
    }

    public function getExternalPath() : Path
    {
        return (\call_user_func([self::$outer_eye, 'instance']))->getExternalPath();
    }

    public function getInternalPath() : Path
    {
        return (\call_user_func([self::$inner_eye, 'instance']))->getInternalPath();
    }
}


