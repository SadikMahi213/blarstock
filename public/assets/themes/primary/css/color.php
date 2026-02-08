<?php

header("Content-Type:text/css");

function checkHexColor($color): bool|int
{
    return preg_match('/^#[a-f0-9]{6}$/i', $color);
}

if (isset($_GET['color1']) and $_GET['color1'] != '') {
    $color1 = "#" . $_GET['color1'];
}

if (!$color1 or !checkHexColor($color1)) {
    $color1 = "#336699";
}

if (isset($_GET['color2']) and $_GET['color2'] != '') {
    $color2 = "#" . $_GET['color2'];
}

if (!$color2 or !checkHexColor($color2)) {
    $color2 = "#336699";
}

function hexToHsl($hex): array
{
    $hex   = str_replace('#', '', $hex);
    $red   = hexdec(substr($hex, 0, 2)) / 255;
    $green = hexdec(substr($hex, 2, 2)) / 255;
    $blue  = hexdec(substr($hex, 4, 2)) / 255;

    $cMin  = min($red, $green, $blue);
    $cMax  = max($red, $green, $blue);
    $delta = $cMax - $cMin;

    if ($delta == 0) {
        $hue = 0;
    } elseif ($cMax === $red) {
        $hue = (($green - $blue) / $delta);
    } elseif ($cMax === $green) {
        $hue = ($blue - $red) / $delta + 2;
    } else {
        $hue = ($red - $green) / $delta + 4;
    }

    $hue = round($hue * 60);

    if ($hue < 0) $hue += 360;

    $lightness  = ($cMax + $cMin) / 2;
    $saturation = $delta === 0 ? 0 : ($delta / (1 - abs(2 * $lightness - 1)));

    if ($saturation < 0) $saturation += 1;

    $lightness  = round($lightness * 100);
    $saturation = round($saturation * 100);

    $hsl['h'] = $hue;
    $hsl['s'] = $saturation;
    $hsl['l'] = $lightness;

    return $hsl;
}

?>

:root{
    --base-h: <?php echo hexToHsl($color1)['h']; ?>;
    --base-s: <?php echo hexToHsl($color1)['s'] . '%'; ?>;
    --base-l: <?php echo hexToHsl($color1)['l'] . '%'; ?>;
    --base-two-h: <?php echo hexToHsl($color2)['h']; ?>;
    --base-two-s: <?php echo hexToHsl($color2)['s'] . '%'; ?>;
    --base-two-l: <?php echo hexToHsl($color2)['l'] . '%'; ?>;
}
