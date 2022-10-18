<?php

/**
 * Description of Utils
 *
 * @author andryukovaa
 */
class Utils {

    /**
     * Description of injectText2File
     *
     * @file File to replace piece of text @var with @text
     * @var old text in @file to be searched and replaced
     * @text new text to replace @var
     */
    static function injectText2File($file, $var, $text) {
        $Result = array('status' => 'error', 'message' => '');
        if (file_exists($file) === TRUE) {
            if (is_writeable($file)) {
                try {
                    $FileContent = file_get_contents($file);
                    $FileContent = str_replace($var, $text, $FileContent);
                    if (file_put_contents($file, $FileContent) > 0) {
                        $Result["status"] = 'success';
                    } else {
                        $Result["message"] = 'Error while writing file';
                    }
                } catch (Exception $e) {
                    $Result["message"] = 'Error : ' . $e;
                }
            } else {
                $Result["message"] = 'File ' . $file . ' is not writable !';
            }
        } else {
            $Result["message"] = 'File ' . $file . ' does not exist !';
        }
        return $Result;
    }

    static function customJSFromFile($file, $oldtext = '', $newtext = '') {
        if (file_exists($file)) {
            $customjs = file_get_contents($file);
            if ($oldtext && $newtext) {
                $customjs = str_replace($oldtext, $newtext, $customjs);
            }
            return '<script>' . $customjs . '</script>';
        } else {
            return '<script>console.log("File ' . $file . ' not found!")</script>';
        }
    }

        static function cleanArrayByKeys($array, $keys) {
            $arr = [];
            foreach ($array as $key => $value) {
                if (in_array($key, $keys)) {
                    $arr[$key] = $value;
                }
            }
            return $arr;
        }

}
