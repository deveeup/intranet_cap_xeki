<?php
namespace ag_img_resize;

// error_reporting(0);

class resize
{
    // 	*** Class variables
    private $image;
    private $width;
    private $height;
    private $imageResized;

    private $folder;
    private $method;
    private $kraken_api_key;
    private $kraken_api_secret;


    function __construct($config)
    {
        $this->folder = $config['folder'];
        $this->method = $config['method'];
        $this->kraken_api_key = $config['kraken_api_key'];
        $this->kraken_api_secret = $config['kraken_api_secret'];
    }


    function resize($PATH_IMAGE, $CODE = '', $MOBILE_DOUBLE = false)
    {
        if ($this->method == "kraken") {
            return $this->kraken_resize($PATH_IMAGE, $CODE, $MOBILE_DOUBLE);
        } else if ($this->method == "local") {
            return $this->local_resize($PATH_IMAGE, $CODE, $MOBILE_DOUBLE);
        }
    }

    function kraken_resize($PATH_IMAGE, $CODE = '', $MOBILE_DOUBLE = false)
    {
        try {
            $dimensions = explode('x', $CODE);

            $width = $dimensions[0];
            $height = $dimensions[1];

            if ($MOBILE_DOUBLE) {
                if (ag_isMobile_ios()) {
                    $width = is_numeric($width) ? ($width * 2) : $width;
                    $height = is_numeric($height) ? ($height * 2) : $height;
                }
            }

            $CODE = $width . 'x' . $height;
            $PATH_IMAGE_ARRAY = explode('/', $PATH_IMAGE);
            $name = $PATH_IMAGE_ARRAY[count($PATH_IMAGE_ARRAY) - 1];

            $src_return = str_replace(' ', '_', 'static_files/img/resizes/' . $CODE . $name);

            if (!file_exists($PATH_IMAGE)) {
                $PATH_IMAGE = str_replace('/upload/', 'admin/upload/', $PATH_IMAGE);
            }

            if (!file_exists($src_return) && file_exists($PATH_IMAGE)) {

                $folder = 'static_files/img/resizes/';
                $path = realpath($folder);
                if ($path === false AND !is_dir($folder)) {
                    mkdir(dirname(__FILE__) . "/../../../static_files", 0777, true);
                    mkdir(dirname(__FILE__) . "/../../../static_files/img", 0777, true);
                    mkdir(dirname(__FILE__) . "/../../../static_files/img/resizes", 0777, true);
                }

                require_once(dirname(__FILE__) . "/libs/vendor/kraken-io/kraken-php/lib/Kraken.php");

                // kraken run
                set_time_limit(60);
                ini_set('memory_limit','16M');
                $kraken = new \Kraken($this->kraken_api_key, $this->kraken_api_secret);
                $params = array(
                    "file" => dirname(__FILE__) . "/../../../$PATH_IMAGE",
                    "wait" => true,
                    "resize" => array(
                        "webp" => true,
                        "strategy" => "auto"
                    ),
                    "webp" => true,
                    "lossy" => true
                    // "lossy" => false,
                );

                if ($width != "auto") $params['resize']['width'] = $width;
                else $params['resize']['strategy'] = "portrait"; # for height fix

                if ($height != "auto") $params['resize']['height'] = $height;
                else  $params['resize']['strategy'] = "landscape"; # for widht fix

                $now = time();
                $data = $kraken->upload($params);
                // 				check if folder exist
                if (isset($data['kraked_url'])) {
                    file_put_contents("$src_return", fopen("{$data['kraked_url']}", 'r'));
                }
//                d(time()-$now);
            }


            // 			fix error
            if (!file_exists($src_return)) {
                $src_return = $PATH_IMAGE;
            }

            return './' . $src_return;

        } catch
        (Exception $e) {
            return './' . $PATH_IMAGE;

        }

    }

    function local_resize($PATH_IMAGE, $CODE = '', $MOBILE_DOUBLE = false)
    {
        try {
            $dimensions = explode('x', $CODE);
            $width = $dimensions[0];
            $height = $dimensions[1];
            if ($MOBILE_DOUBLE) {
                if (ag_isMobile_ios()) {
                    $width = is_numeric($width) ? ($width * 2) : $width;
                    $height = is_numeric($height) ? ($height * 2) : $height;
                }
            }

            $CODE = $width . 'x' . $height;
            // 			d($PATH_IMAGE);
            // 			d($CODE);
            $PATH_IMAGE_ARRAY = explode('/', $PATH_IMAGE);
            $name = $PATH_IMAGE_ARRAY[count($PATH_IMAGE_ARRAY) - 1];
            $src_return = str_replace(' ', '_', 'static_files/img/resizes/' . $CODE . $name);

            if (!file_exists($PATH_IMAGE)) {
                $PATH_IMAGE = str_replace('/upload/', 'admin/upload/', $PATH_IMAGE);

            }

            if (!file_exists($src_return) && file_exists($PATH_IMAGE)) {

                $folder = 'static_files/img/resizes/';
                $path = realpath($folder);
                if ($path === false AND !is_dir($folder)) {
                    mkdir(dirname(__FILE__) . "/../../../static_files", 0777, true);
                    mkdir(dirname(__FILE__) . "/../../../static_files/img", 0777, true);
                    mkdir(dirname(__FILE__) . "/../../../static_files/img/resizes", 0777, true);
                }

                $this->check_and_local_resize($PATH_IMAGE, $CODE, $src_return, $MOBILE_DOUBLE);

            }

            if (!file_exists($src_return)) {
                $src_return = $PATH_IMAGE;

            }

            return './' . $src_return;

        } catch
        (Exception $e) {
            return './' . $PATH_IMAGE;

        }

    }


    function check_and_local_resize($PATH, $CODE, $src_return)
    {
        $errorlevel = error_reporting();

        // 		error_reporting(0);

        $this->init($PATH);

        $dimensions = explode('x', $CODE);

        $width = $dimensions[0];

        $height = $dimensions[1];

        if (is_numeric($width) && is_numeric($height)) {
            if ($this->width >= $width && $this->height >= $height) {
                $this->resizeImage($width, $height);

            }

            $this->saveImage($src_return);

        } elseif (is_numeric($width)) {
            if ($this->width >= $width) {
                $height = $this->getSizeByFixedWidth($width);

                $this->resizeImage($width, $height);

            }

            $this->saveImage($src_return);

        } elseif (is_numeric($height)) {
            if ($this->height >= $height) {
                $width = $this->getSizeByFixedHeight($height);

                $this->resizeImage($width, $height);

            }

            $this->saveImage($src_return);

        } else {

        }

        error_reporting($errorlevel);


    }


    function init($fileName)
    {
        // 		*** Open up the file
        $this->image = $this->openImage($fileName);


        // 		*** Get width and height
        $this->width = imagesx($this->image);

        $this->height = imagesy($this->image);

    }


    ## --------------------------------------------------------

    private function openImage($file)
    {
        // 		*** Get extension
        $extension = strtolower(strrchr($file, '.'));


        switch ($extension) {
            case '.jpg':
            case '.jpeg':
                $img = @imagecreatefromjpeg($file);

                break;

            case '.gif':
                $img = @imagecreatefromgif($file);

                break;

            case '.png':
                $img = @imagecreatefrompng($file);

                break;

            default:
                $img = false;

                break;

        }

        return $img;

    }


    ## --------------------------------------------------------

    public function resizeImage($newWidth, $newHeight, $option = "auto")
    {
        // 		*** Get optimal width and height - based on $option
        $optionArray = $this->getDimensions($newWidth, $newHeight, $option);


        $optimalWidth = $optionArray['optimalWidth'];

        $optimalHeight = $optionArray['optimalHeight'];


        // 		*** Resample - create image canvas of x, y size
        // 		$newImg = imagecreatetruecolor($nWidth, $nHeight);

        // 		imagealphablending($newImg, false);

        // 		imagesavealpha($newImg,true);

        // 		$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);

        // 		imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);

        // 		imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight,$imgInfo[0], $imgInfo[1]);


        $this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);

        imagealphablending($this->imageResized, false);

        imagesavealpha($this->imageResized, true);

        $transparent = imagecolorallocatealpha($this->imageResized, 255, 255, 255, 127);

        imagefilledrectangle($this->imageResized, 0, 0, $optimalWidth, $optimalHeight, $transparent);

        imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);


        // 		*** if option is 'crop', then crop too
        if ($option == 'crop') {
            $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);

        }

    }


    ## --------------------------------------------------------

    private function getDimensions($newWidth, $newHeight, $option)
    {

        switch ($option) {
            case 'exact':
                $optimalWidth = $newWidth;

                $optimalHeight = $newHeight;

                break;

            case 'portrait':
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);

                $optimalHeight = $newHeight;

                break;

            case 'landscape':
                $optimalWidth = $newWidth;

                $optimalHeight = $this->getSizeByFixedWidth($newWidth);

                break;

            case 'auto':
                $optionArray = $this->getSizeByAuto($newWidth, $newHeight);

                $optimalWidth = $optionArray['optimalWidth'];

                $optimalHeight = $optionArray['optimalHeight'];

                break;

            case 'crop':
                $optionArray = $this->getOptimalCrop($newWidth, $newHeight);

                $optimalWidth = $optionArray['optimalWidth'];

                $optimalHeight = $optionArray['optimalHeight'];

                break;

        }

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);

    }


    ## --------------------------------------------------------

    private function getSizeByFixedHeight($newHeight)
    {
        $ratio = $this->width / $this->height;

        $newWidth = $newHeight * $ratio;

        return $newWidth;

    }


    private function getSizeByFixedWidth($newWidth)
    {
        $ratio = $this->height / $this->width;

        $newHeight = $newWidth * $ratio;

        return $newHeight;

    }


    private function getSizeByAuto($newWidth, $newHeight)
    {
        if ($this->height < $this->width) // 		*** Image to be resized is wider (landscape)
        {
            $optimalWidth = $newWidth;

            $optimalHeight = $this->getSizeByFixedWidth($newWidth);

        } elseif ($this->height > $this->width) // 		*** Image to be resized is taller (portrait)
        {
            $optimalWidth = $this->getSizeByFixedHeight($newHeight);

            $optimalHeight = $newHeight;

        } else // 		*** Image to be resizerd is a square
        {
            if ($newHeight < $newWidth) {
                $optimalWidth = $newWidth;

                $optimalHeight = $this->getSizeByFixedWidth($newWidth);

            } else if ($newHeight > $newWidth) {
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);

                $optimalHeight = $newHeight;

            } else {
                // 				*** Sqaure being resized to a square
                $optimalWidth = $newWidth;

                $optimalHeight = $newHeight;

            }

        }


        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);

    }


    ## --------------------------------------------------------

    private function getOptimalCrop($newWidth, $newHeight)
    {

        $heightRatio = $this->height / $newHeight;

        $widthRatio = $this->width / $newWidth;


        if ($heightRatio < $widthRatio) {
            $optimalRatio = $heightRatio;

        } else {
            $optimalRatio = $widthRatio;

        }


        $optimalHeight = $this->height / $optimalRatio;

        $optimalWidth = $this->width / $optimalRatio;


        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);

    }


    ## --------------------------------------------------------

    private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
    {
        // 		*** Find center - this will be used for the crop
        $cropStartX = ($optimalWidth / 2) - ($newWidth / 2);

        $cropStartY = ($optimalHeight / 2) - ($newHeight / 2);


        $crop = $this->imageResized;

        //i		magedestroy($this->imageResized);


        // 		*** Now crop from center to exact requested size
        $this->imageResized = imagecreatetruecolor($newWidth, $newHeight);

        imagecopyresampled($this->imageResized, $crop, 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight, $newWidth, $newHeight);

    }


    ## --------------------------------------------------------

    public function saveImage($savePath, $imageQuality = "100")
    {
        // 		*** Get extension
        $extension = strrchr($savePath, '.');

        $extension = strtolower($extension);


        switch ($extension) {
            case '.jpg':
            case '.jpeg':
                if (imagetypes() & IMG_JPG) {
                    if ($this->imageResized !== null)
                        imagejpeg($this->imageResized, $savePath, $imageQuality);

                }

                break;


            case '.gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($this->imageResized, $savePath);

                }

                break;


            case '.png':
                // 			*** Scale quality from 0-100 to 0-9
                $scaleQuality = round(($imageQuality / 100) * 9);


                // 			*** Invert quality setting as 0 is best, not 9
                $invertScaleQuality = 9 - $scaleQuality;


                if (imagetypes() & IMG_PNG) {
                    imagepng($this->imageResized, $savePath, $invertScaleQuality);

                }

                break;


            // 			... etc

            default:
                // 			*** No extension - No save.
                break;

        }

        if ($this->imageResized !== null)
            imagedestroy($this->imageResized);

        // 		compressImageVersion();

        // 		$this->compressImage($savePath);

    }


    public function compressImageVersion($savePath)
    {
        $kraken = new Kraken("your-api-key", "your-api-secret");


        $params = array(
            "file" => "/path/to/image/file.jpg",
            "wait" => true,
            "lossy" => true
        );


        $data = $kraken->upload($params);

        $result = fopen($url, "r", false, stream_context_create($options));

        if ($result) {

            /* Compression was successful, retrieve output from Location header. */

            foreach ($http_response_header as $header) {
                if (strtolower(substr($header, 0, 10)) === "location: ") {
                    file_put_contents($output, fopen(substr($header, 10), "rb", false));

                }

            }

        } else {

            /* Something went wrong! */

            print("Compression failed");

        }

    }


    public function compressImage($savePath)
    {
        $key = "BsSu472rgQK_4Zku5zl0-jrZm1nQA6OF";

        $input = $savePath;

        $output = $savePath;

        $infoExp = explode('.', $savePath);

        $url = "https://api.tinify.com/shrink";

        $options = array(
            "http" => array(
                "method" => "POST",
                "header" => array(
                    "Content-type: image/" . $infoExp[count($infoExp) - 1],
                    "Authorization: Basic " . base64_encode("api:$key")
                ),
                "content" => file_get_contents($input)
            ),
            "ssl" => array(

                /* Uncomment below if you have trouble validating our SSL certificate.
                           Download cacert.pem from: http://curl.haxx.se/ca/cacert.pem */

                // 		"cafile" => __DIR__ . "/cacert.pem",
                "verify_peer" => true
            )
        );


        $result = fopen($url, "r", false, stream_context_create($options));

        if ($result) {

            /* Compression was successful, retrieve output from Location header. */

            foreach ($http_response_header as $header) {
                if (strtolower(substr($header, 0, 10)) === "location: ") {
                    file_put_contents($output, fopen(substr($header, 10), "rb", false));

                }

            }

        } else {

            /* Something went wrong! */

            print("Compression failed");

        }

    }

}
