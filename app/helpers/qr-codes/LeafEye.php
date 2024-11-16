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
use BaconQrCode\Renderer\Path\Path;
use SimpleSoftwareIO\QrCode\Singleton;

final class LeafEye implements EyeInterface, Singleton
{
    private static $instance;

    private function __construct()
    {
    }

    public static function instance() : self
    {
        return self::$instance ?: self::$instance = new self();
    }

    public function getExternalPath() : Path
    {
        return (new Path())
            ->move(-3.5, 3.5)
            ->curve(-3.5, -3.5, -3.5, -3.5, 3.5, -3.5)
            ->move(3.5, -3.5)
            ->curve(3.5, 3.5, 3.5, 3.5, -3.5, 3.5)
            ->close()
            ->move(-2.5, 2.5)
            ->curve(-2.5, -2.5, -2.5, -2.5, 2.5, -2.5)
            ->move(2.5, -2.5)
            ->curve(2.5, 2.5, 2.5, 2.5, -2.5, 2.5)
            ;
    }

    public function getInternalPath() : Path
    {
        return (new Path())
            ->move(-1.5, 1.5)
            ->curve(-1.5, -1.5, -1.5, -1.5, 1.5, -1.5)
            ->move(1.5, -1.5)
            ->curve(1.5, 1.5, 1.5, 1.5, -1.5, 1.5)
            ;
    }
}
