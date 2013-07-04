<?php
/**
 * 
 * https://developers.google.com/chart/interactive/docs/reference
 * 
 * The DataTable object is used to hold the data passed into a visualization. 
 * A DataTable is a basic two-dimensional table. All data in each column must have the same data type. 
 * Each column has a descriptor that includes its data type, a label for that column (which might be displayed by a visualization), and an ID, which can be used to refer to a specific column (as an alternative to using column indexes). 
 * The DataTable object also supports a map of arbitrary properties assigned to a specific value, a row, a column, or the whole DataTable. Visualizations can use these to support additional features; for example, the Table visualization uses custom properties to let you assign arbitrary class names or styles to individual cells.
 * 
 * cols Property : cols is an array of objects describing the ID and type of each column. 
 * Each property is an object with the following properties (case-sensitive):
 * type [Required] Data type of the data in the column. Supports the following string values (examples include the v: property, described later):
 * 'boolean' - JavaScript boolean value ('true' or 'false'). Example value: v:'true'
 * 'number' - JavaScript number value. Example values: v:7 , v:3.14, v:-55
 * 'string' - JavaScript string value. Example value: v:'hello'
 * 'date' - JavaScript Date object (zero-based month), with the time truncated. Example value: v:new Date(2008, 0, 15)
 * 'datetime' - JavaScript Date object including the time. Example value: v:new Date(2008, 0, 15, 14, 30, 45)
 * 'timeofday' - Array of three numbers and an optional fourth, representing hour (0 indicates midnight), minute, second, and optional millisecond. Example values: v:[8, 15, 0], v: [6, 12, 1, 144]
 * id [Optional] String ID of the column. Must be unique in the table. Use basic alphanumeric characters, so the host page does not require fancy escapes to access the column in JavaScript. Be careful not to choose a JavaScript keyword. Example: id:'col_1'
 * label [Optional] String value that some visualizations display for this column. Example: label:'Height'
 * pattern [Optional] String pattern that was used by a data source to format numeric, date, or time column values. This is for reference only; you probably won't need to read the pattern, and it isn't required to exist. The Google Visualization client does not use this value (it reads the cell's formatted value). If the DataTable has come from a data source in response to a query with a format clause, the pattern you specified in that clause will probably be returned in this value. The recommended pattern standards are the ICU DecimalFormat and SimpleDateFormat.
 * p [Optional] An object that is a map of custom values applied to the cell. These values can be of any JavaScript type. If your visualization supports any cell-level properties, it will describe them; otherwise, this property will be ignored. Example: p:{style: 'border: 1px solid green;'}.
 * cols Example

 * cols: [{id: 'A', label: 'NEW A', type: 'string'},
 * {id: 'B', label: 'B-label', type: 'number'},
 * {id: 'C', label: 'C-label', type: 'date'}]
 *   
 * @link https://developers.google.com/chart/interactive/docs/reference
 * @author Nikhil Patil <nikhil dot p dot nik at gmail dot com> 
 * @author Nikhil Patil 
 * @package GoogleCharts
 * @version 1.0.1
 */

require_once "Base.php";

class GoogleCharts extends Base {

    /**
     * @uses define chart type
     * @var string  $_chartType
     */
    private $_chartType = NULL;

    /**
     * @uses to store the rows value
     * @var array  $_row
     */
    private $_cols = array();

    /**
     * @uses to store the rows value
     * @var array  $_row
     */
    private $_rows = array();

    /**
     * @uses store Json Value
     * @var json $_JSON; 
     */
    private $_JSON = NULL;

    /**
     *
     * @var Boolean $_rowEmpty; 
     */
    private $_rowEmpty = FALSE;

    /**
     * Added Constant for the GOOGLE API Script. 
     */

    CONST GOOGLEJSAPI = "<script type='text/javascript' src='https://www.google.com/jsapi'></script>";

    /**
     * @name __construct
     * @uses initialize the set values
     * @param array $options 
     */
    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
        $this->_JSON = $this->generateClass();
    }

    /**
     * @name setChartType
     * @uses set the default value for chart type
     * @param type $data
     * @return \GoogleCharts 
     */
    public function setChartType($data) {
        $this->_chartType = $data;
        return $this;
    }

    /**
     * @name getChartType
     * @uses get the default value for chart type
     * @param null
     * @return $this->_chartType 
     */
    public function getChartType() {
        return $this->_chartType;
    }

    /**
     * @name setCols
     * @uses set the default value for cols
     * @param array $arrData
     * @return \GoogleCharts 
     */
    public function setCols(array $arrData) {
        $this->_cols = $arrData;
        return $this;
    }

    /**
     * @name getCols
     * @uses get the default value for cols
     * @param null
     * @return $this->_cols 
     */
    public function getCols() {
        return $this->_cols;
    }

    /**
     * @name setRows
     * @uses set the default value for Rows
     * @param array $arrData
     * @return \GoogleCharts 
     */
    public function setRows(array $arrData) {
        $this->_rows = $arrData;
        return $this;
    }

    /**
     * @name getRows
     * @uses get the default value for Rows
     * @param null
     * @return $this->_rows 
     */
    public function getRows() {
        return $this->_rows;
    }

    /**
     * @name getJSONHeader
     * @uses Convert 2-D Array to google Json Data format
     * @param null
     * @return google json data
     */
    public function getJSONHeader() {
        $this->analyzeCols();
        $this->analyzeRows();
        ob_flush();
        header('Content-type: application/json');
        return json_encode($this->_JSON);
        exit(1);
    }

    /**
     * @method setRowsEmpty
     * @uses set empty row check
     * @param type $val
     * @return \GoogleCharts 
     */
    public function setRowsEmpty($val) {
        $this->_rowEmpty = $val;
        return $this;
    }

    /**
     * @method getRowsEmpty
     * @uses get empty row check
     * @param null
     * @return $this->_rowEmpty 
     */
    public function getRowsEmpty() {
        return $this->_rowEmpty;
    }

    /**
     * @name getJSONString
     * @uses Convert 2-D Array to google Json Data string
     * @param null
     * @return google json data 
     */
    public function getJSONString() {
        $this->analyzeCols();
        $this->analyzeRows();
        return json_encode($this->_JSON);
    }

    /**
     * @name getQueryResponse
     * @uses Convert 2-D Array to google Json Data Query Response
     * @param null
     * @return google json data 
     */
    public function getQueryResponse() {
        $json = $this->getJSON();
        $queryRespone = "google.visualization.Query.setResponse({'version':'0.6','status':'ok','sig':'1029305520','table':{";

        if (!is_null($json)) {
            $queryRespone .=$json;
        }
        $queryRespone .= "}});";

        return $queryRespone;
    }

    /**
     * @name analyzeCols
     * @uses Analyze the columns
     * @param null
     * @return \GoogleCharts  
     */
    public function analyzeCols() {
        $cols = $this->getCols();

        if (empty($cols)) {
            throw new Exception("COLS is Empty");
            exit(1);
        }

        $this->_JSON->cols = array();
        foreach ($cols as $val) {
            $objCols = $this->generateClass();
            $objCols->id = $val['id'];
            $objCols->label = $val['id'];
            $objCols->type = $val['type'];
            $this->_JSON->cols[] = $objCols;
        }

        return $this;
    }

    /**
     * @name analyzeRows
     * @uses Analyze the rows
     * @param null
     * @return \GoogleCharts  
     */
    public function analyzeRows() {
        $rows = $this->getRows();
        $this->_JSON->rows = array();
        if (empty($rows)) {
            $objRows = $this->generateClass();
            $objRows->c = array();
            foreach ($this->_JSON->cols as $obj) {
                $objValue = $this->generateClass();
                $objValue->v = 0;
                $objRows->c[] = $objValue;
            }
            $this->_JSON->rows[] = $objRows;
        } else {
            foreach ($rows as $row) {
                $objRows = $this->generateClass();
                $objRows->c = array();
                foreach ($this->_JSON->cols as $obj) {

                    $objValue = $this->generateClass();
                    if (isset($row[$obj->id])) {
                        $objValue->v = $row[$obj->id];
                    } else {
                        $objValue->v = 0;
                    }

                    $objRows->c[] = $objValue;
                }
                $this->_JSON->rows[] = $objRows;
            }
        }

        return $this;
    }

}

/**
 * Supplementary json_encode in case php version is < 5.2 (taken from http://gr.php.net/json_encode)
 */
if (!function_exists('json_encode')) {

    function json_encode($a = false) {
        if (is_null($a))
            return 'null';
        if ($a === false)
            return 'false';
        if ($a === true)
            return 'true';
        if (is_scalar($a)) {
            if (is_float($a)) {
                // Always use "." for floats.
                return floatval(str_replace(",", ".", strval($a)));
            }

            if (is_string($a)) {
                static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
                return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
            }
            else
                return $a;
        }
        $isList = true;
        for ($i = 0, reset($a); $i < count($a); $i++, next($a)) {
            if (key($a) !== $i) {
                $isList = false;
                break;
            }
        }
        $result = array();
        if ($isList) {
            foreach ($a as $v)
                $result[] = json_encode($v);
            return '[' . join(',', $result) . ']';
        } else {
            foreach ($a as $k => $v)
                $result[] = json_encode($k) . ':' . json_encode($v);
            return '{' . join(',', $result) . '}';
        }
    }

}