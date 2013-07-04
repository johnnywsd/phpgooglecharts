<?php
/**
 * @link https://developers.google.com/chart/interactive/docs/reference
 * @author Nikhil Patil <nikhil dot p dot nik at gmail dot com> 
 * @author Nikhil Patil 
 * @package GoogleCharts
 * @version 1.0.1
 */
Abstract class Base {

    /**
     * @uses Google Apis URL
     * @var string  $_ChartUrl
     */
    private $_ChartUrl = "https://www.google.com/jsapi";

    /**
     * @method exportToCSV
     * @uses Export to CSV
     * @param string $sql
     * @param array $data
     * @param string $filename 
     */
    public function exportToCSV($sql, array $data, $filename) {
        $results = executeSELECT($sql, $data);
        ob_clean();
        $filename = $filename . '_' . date("Y-m-d") . ".csv";
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Description: File Transfer');
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename={$filename}");
        header("Expires: 0");
        header("Pragma: public");
        ob_start();
        $df = @fopen("php://output", 'w');
        $headerDisplayed = false;
        foreach ($results as $data) {
            if (!$headerDisplayed) {
                fputcsv($df, array_keys($data));
                $headerDisplayed = true;
            }
            fputcsv($df, $data);
        }
        fclose($df);
        echo ob_get_clean();
        exit(1);
    }

    /**
     * @method formatDate
     * @param string $date
     * @return string formatted date 
     */
    public function formatDate($date) {
        $datearray = explode('/', $date);
        $formatted = date("d-M-Y", mktime(0, 0, 0, $datearray[1], $datearray[0], $datearray[2]));
        return $formatted;
    }

    /**
     * @method __set
     * @uses Set the property
     * @param type $name
     * @param type $value
     * @throws Exception 
     */
    public function __set($name, $value) {
        $name = preg_replace_callback('/_(.)/', create_function('$matches', 'return ucfirst($matches[1]);'), $name);
        $method = 'set' . ucfirst($name);
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid User property');
        }
        $this->$method($value);
    }

    /**
     * @method __get
     * @uses Get the property
     * @param type $name
     * @return type
     * @throws Exception 
     */
    public function __get($name) {
        $name = preg_replace_callback('/_(.)/', create_function('$matches', 'return ucfirst($matches[1]);'), $name);
        $method = 'get' . ucfirst($name);

        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid User property');
        }
        return $this->$method();
    }

    /**
     * 
     * @param type $method
     * @param array $args
     * @throws Exception 
     */
    public function __call($method, array $args) {
        throw new Exception("Unrecognized method '$method()'");
    }

    /**
     *
     * @param array $options
     * @return \Base 
     */
    public function setOptions(array $options) {
        $methods = get_class_methods($this);

        foreach ($options as $key => $value) {
            $key = preg_replace_callback('/_(.)/', create_function('$matches', 'return ucfirst($matches[1]);'), $key);
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * @method getScript
     * @return string 
     */
    public function getScript() {
        $script = self::GOOGLEJSAPI . '<script src="' . WELC_ADMIN_ROOT . '/commons/javascripts/google.charts.js" type="text/javascript"></script>';
        return $script;
    }

    /**
     * @method generateClass
     * @return \StdClass 
     */
    public function generateClass() {
        return new StdClass;
    }

}

if (!function_exists('fputcsv')) {

    function fputcsv(&$handle, $fields = array(), $delimiter = ',', $enclosure = '"') {
        $str = '';
        $escape_char = '\\';
        foreach ($fields as $value) {
            if (strpos($value, $delimiter) !== false ||
                    strpos($value, $enclosure) !== false ||
                    strpos($value, "\n") !== false ||
                    strpos($value, "\r") !== false ||
                    strpos($value, "\t") !== false ||
                    strpos($value, ' ') !== false) {
                $str2 = $enclosure;
                $escaped = 0;
                $len = strlen($value);
                for ($i = 0; $i < $len; $i++) {
                    if ($value[$i] == $escape_char) {
                        $escaped = 1;
                    } else if (!$escaped && $value[$i] == $enclosure) {
                        $str2 .= $enclosure;
                    } else {
                        $escaped = 0;
                    }
                    $str2 .= $value[$i];
                }
                $str2 .= $enclosure;
                $str .= $str2 . $delimiter;
            } else {
                $str .= $value . $delimiter;
            }
        }
        $str = substr($str, 0, -1);
        $str .= "\n";
        return fwrite($handle, $str);
    }

}