<?php

class i extends app
{

    public function __construct(&$parent)
    {
        parent::__construct($parent);

        $this->load->app('items');

    }

    private $borderpath = 'images/tooltip/';
    private $fontface = 'fonts/arialbd.ttf';
    private $footerfont = 'fonts/arialbd.ttf';
    //private $fontface = 'fonts/msjhbd.ttf';
    //private $footerfont = 'fonts/msjhbd.ttf';
    private $fontsize = 10.5;
    private $namesize = 13.5;
    //private $fontsize = 10;
    //private $namesize = 13;
    private $footersize = 9;
    private $imgfooter = 'https://aoedb.net';

    public function c2_index($dbid, $level)
    {
        print_r(headers_list());
        $this->loadfile('images/cache/5428/448_39.png');
    }

    public function c_index($dbid = null, $level = null)
    {

        $level = str_replace('.png', '', $level);
        //$level = $level-3;

        if (!is_numeric($dbid) || !is_numeric($level)) {
            $this->error("Invalid input. Format is aoedb.net/i/DBID/level.png");
            return;
        }

        $dbid = intval($dbid);
        $level = intval($level);

        //$folder = "images/cache/{$this->config['buildnum']}/{$dbid}";
        //$folder = "images/cache/" . strval($this->aoeo->config['build']);
        $folder = "images/cache/5491";
        //$this->error($folder);
        //return;
        $filename = "{$folder}/{$dbid}_{$level}.png";

        // Valid input, load image if exists:
        if (file_exists($filename)) {
            $this->loadfile($filename);
            return;
        }

        if ($level < 1 || $level > 40) {
            $this->error("Level {$level} is invalid, must be from 1 to 40");
            return;
        }

        if (!$item = $this->items->getImgData($dbid, $level)) {
            $this->error("DBID {$dbid} not found");
            return;
        }

        // Make and save image.
        $this->generate($item, $folder, $filename);

    }

    private function generate($item, $folder, $filename)
    {
        // This is a bit more hard-coded than error, for my sanity.
        $width = 316;
        /*
        8 - left border
        2 - top padding
        2 - left padding (text)
        4 - left padding (icon)
        64 - icon size
        6 - left padding (name text)
        4 - right padding
        8 - right border

        type: x = 10, y = 8+14 = 22
        rarity: x = 316-8-4-size, y = 22
        icon: x = 10, y = 36
        name: x = 80, y = 38+namesize/.75, width = 224
        description: x = 10, y = 124, width =

         */

        $rarity_size = imagettfbbox($this->fontsize, 0, $this->fontface, $item['rarity']);
        $displayNameWrap = $this->wrap($this->namesize, 0, $this->fontface, $item['displayName'], 224);
        $descriptionWrap = $this->wrap($this->fontsize, 0, $this->fontface, $item['description'], 294);

        $descriptionWrap = explode("\n", $descriptionWrap);
        for ($i = 0; $i < count($descriptionWrap); $i++) {
            if (strlen($descriptionWrap[$i]) > 1) {
                $descriptionSize[$i] = 18;
            } else {
                $descriptionSize[$i] = 12;
            }

        }

        $height = 120 + array_sum($descriptionSize) + 26 + 18 * count($item['effects']);

        $im = imagecreatetruecolor($width, $height);
        imagesavealpha($im, true);
        imagealphablending($im, true);
        imagefill($im, 0, 0, imagecolorallocatealpha($im, 0, 0, 0, 127));

        imagefilledrectangle($im, 8, 8, $width - 8, $height - 8, imagecolorallocate($im, 49, 61, 82));
        $this->border($im, $height, $width);

        $this->imagetext_shadow($im, $this->fontsize, 0, 10, 22, imagecolorallocate($im, 230, 201, 83), $this->fontface, $item['type']);
        $this->imagetext_shadow($im, $this->fontsize, 0, 304 - $rarity_size[4], 22, $this->imagecolorallocate_hex($im, $item['rarityColor']), $this->fontface, $item['rarity']);

        $iconshadow = imagecreatefrompng('images/icon_shadow2.png');
        $iconim = imagecreatefrompng($item['icon']);

        $iconx = 10;
        $icony = 36;
        imagecopyresampled($im, $iconshadow, $iconx + 1, $icony + 1, 0, 0, 64, 64, 64, 64);
        imagecopyresampled($im, $iconim, $iconx, $icony, 0, 0, 64, 64, 64, 64);

        $this->imagetext_shadow($im, $this->namesize, 0, 80, 38 + $this->namesize / 0.75, $this->imagecolorallocate_hex($im, $item['rarityColor']), $this->fontface, $displayNameWrap);

        for ($i = 0; $i < count($descriptionWrap); $i++) {
            if ($i == 0) {
                $addedheight = 0;
            } else {
                $addedheight = array_sum(array_slice($descriptionSize, 0, $i));
            }

            $this->imagetext_shadow($im, $this->fontsize, 0, 10, 120 + $addedheight, imagecolorallocate($im, 255, 255, 255), $this->fontface, $descriptionWrap[$i]);
        }

        $effects_y = 120 + array_sum($descriptionSize) + 8;

        $bullet = imagecreatefrompng('images/bullet.png');
        for ($i = 0; $i < count($item['effects']); $i++) {
            $this->imagetext_shadow($im, $this->fontsize, 0, 30, $effects_y + 18 * $i, $this->imagecolorallocate_hex($im, $item['effectColors'][$i]), $this->fontface, $item['effects'][$i]);
            imagecopyresampled($im, $bullet, 17, $effects_y - 14 + 18 * $i, 0, 0, 8, 14, 8, 14);
        }

        $this->imagetext_shadow($im, $this->footersize, 0, 10, $height - 10, imagecolorallocate($im, 255, 255, 255), $this->footerfont, $item['dbid_str']);

        $this->imagetext_shadow($im, $this->footersize, 0, 220, $height - 10, imagecolorallocate($im, 255, 255, 255), $this->footerfont, 'www.aoedb.net');

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        imagepng($im, $filename);

        header('Content-Type: image/png');
        imagepng($im);
        imagedestroy($im);

    }

    private function loadfile($filename)
    {
        header('Content-Type: image/png');
        $im = imagecreatefrompng($filename);

        imagesavealpha($im, true);
        imagepng($im);
        imagedestroy($im);
    }

    private function error($err_str)
    {
        $width = 316;

        $err_str = $this->wrap($this->fontsize, 0, $this->fontface, $err_str, $width - 24);
        $err_size = imagettfbbox($this->fontsize, 0, $this->fontface, $err_str);
        $footer_size = imagettfbbox($this->footersize, 0, $this->fontface, $this->imgfooter);

        $height = 24 + $err_size[1] + $this->fontsize / 0.75 + 10 + $this->footersize / 0.75;
        $width = 24 + max($err_size[2], $footer_size[2]);

        $im = imagecreatetruecolor($width, $height);
        imagesavealpha($im, true);
        imagefill($im, 0, 0, imagecolorallocatealpha($im, 0, 0, 0, 127));

        imagefilledrectangle($im, 8, 8, $width - 8, $height - 8, imagecolorallocate($im, 49, 61, 82));
        $this->border($im, $height, $width);

        $this->imagetext_shadow($im, $this->fontsize, 0, 12, 10 + $this->fontsize / 0.75, imagecolorallocate($im, 230, 201, 83), $this->fontface, $err_str);
        $this->imagetext_shadow($im, $this->footersize, 0, 12, 12 + $this->fontsize / 0.75 + $err_size[1] + 10 + $this->footersize / 0.75, imagecolorallocate($im, 250, 250, 250), $this->footerfont, $this->imgfooter);

        header('Content-Type: image/png');
        imagepng($im);
        imagedestroy($im);
    }

    private function wrap($fontSize, $angle, $fontFace, $string, $width)
    {
        // By ben@spooty.net
        $ret = "";
        $arr = explode(' ', $string);
        foreach ($arr as $word) {
            $teststring = $ret . ' ' . $word;
            $testbox = imagettfbbox($fontSize, $angle, $fontFace, $teststring);
            if ($testbox[2] > $width) {
                $ret .= ($ret == "" ? "" : "\n") . $word;
            } else {
                $ret .= ($ret == "" ? "" : ' ') . $word;
            }
        }
        return $ret;
    }

    private function imagetext_shadow(&$im, $fontsize, $angle, $x, $y, $color, $fontfile, $str)
    {
        imagefttext($im, $fontsize, $angle, $x + 1, $y + 1, imagecolorallocate($im, 0, 0, 0), $fontfile, $str);
        imagefttext($im, $fontsize, $angle, $x, $y, $color, $fontfile, $str);
    }

    private function imagecolorallocate_hex(&$im, $rgb)
    {
        $r = base_convert(substr($rgb, 0, 2), 16, 10);
        $g = base_convert(substr($rgb, 2, 2), 16, 10);
        $b = base_convert(substr($rgb, 4, 2), 16, 10);
        return imagecolorallocate($im, $r, $g, $b);
    }

    private function border(&$im, $height, $width)
    {
        $border_topleft = imagecreatefrompng($this->borderpath . 'topleft.png');
        $border_topright = imagecreatefrompng($this->borderpath . 'topright.png');
        $border_botleft = imagecreatefrompng($this->borderpath . 'botleft.png');
        $border_botright = imagecreatefrompng($this->borderpath . 'botright.png');

        $border_left = imagecreatefrompng($this->borderpath . 'left.png');
        $border_right = imagecreatefrompng($this->borderpath . 'right.png');
        $border_top = imagecreatefrompng($this->borderpath . 'top.png');
        $border_bot = imagecreatefrompng($this->borderpath . 'bot.png');

        imagecopyresampled($im, $border_topleft, 0, 0, 0, 0, 8, 8, 8, 8);
        imagecopyresampled($im, $border_topright, $width - 8, 0, 0, 0, 8, 8, 8, 8);
        imagecopyresampled($im, $border_botleft, 0, $height - 8, 0, 0, 8, 8, 8, 8);
        imagecopyresampled($im, $border_botright, $width - 8, $height - 8, 0, 0, 8, 8, 8, 8);

        imagecopyresampled($im, $border_left, 0, 8, 0, 0, 8, $height - 16, 8, 8);
        imagecopyresampled($im, $border_right, $width - 8, 8, 0, 0, 8, $height - 16, 8, 8);
        imagecopyresampled($im, $border_top, 8, 0, 0, 0, $width - 16, 8, 8, 8);
        imagecopyresampled($im, $border_bot, 8, $height - 8, 0, 0, $width - 16, 8, 8, 8);
    }

}

/**end of file*/
