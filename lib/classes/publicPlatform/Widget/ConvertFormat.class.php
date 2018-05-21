<?php
class ConvertFormat
{
    public static function json_encode ($data)
    {
        switch ($type = gettype($data)) {
            case 'NULL':
                $encodedData = 'null';
                break;

            case 'boolean':
                $encodedData = ($data ? 'true' : 'false');
                break;

            case 'integer':
            case 'double':
            case 'float':
                $encodedData = $data;
                break;

            case 'string':
                $encodedData = '"' . addslashes($data) . '"';
                break;

            case 'object':
                $data = get_object_vars($data);
            case 'array':
                $output_index_count = 0;
                $output_indexed = array();
                $output_associative = array();
                foreach ($data as $key => $value) {
                    $output_indexed[] = self::json_encode($value);
                    $output_associative[] = self::json_encode($key) . ':' . self::json_encode($value);
                    if ($output_index_count !== NULL && $output_index_count++ !== $key) {
                        $output_index_count = NULL;
                    }
                }
                if ($output_index_count !== NULL) {
                    $encodedData = '[' . implode(',', $output_indexed) . ']';
                } else {
                    $encodedData = '{' . implode(',', $output_associative) . '}';
                }
                break;

            default:
                $encodedData = ''; // Not supported
                break;
        }

        return $encodedData;
    }

    public static function json_decode ($data, $assoc=false, $depth=512, $options=0)
    {
        if(function_exists('json_decode')) {
            return json_decode($data, $assoc, $depth, $options);
        }

    }


}
