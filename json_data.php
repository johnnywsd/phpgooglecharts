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

require_once "GoogleCharts.php";

/**
 *
 * @param array $result
 * @return JSON 
 */
function getGoogleJsonDataFormat(array $result) {
    $header_check = false;
    $header = array();
    $rows = array();
    if (count($result)) {
        foreach ($result as $data) {
            if (!$header_check) {
                $count = 0;
                foreach (array_keys($data) as $value) {
                    $header[$count]['id'] = $value;
                    $header[$count]['type'] = 'string';
                    $count++;
                }
                $header_check = true;
            }
            $rows[] = $data;
        }
        $chart = new GoogleCharts(array(
                    'chart_type' => 'datatable',
                    'cols' => $header,
                    'rows' => $rows
                ));
        return $chart->getJSONString();
    } else {
        return 0;
    }
}

$data = array(
    0 => array('country' => 'INDIA',
        'population' => '60000'
    ),
    1 => array('country' => 'UNITED STATES AMERICA',
        'population' => '70000'
    )
);


echo $jsondata = getGoogleJsonDataFormat($data);


