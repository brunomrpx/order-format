<?php
namespace app;

/**
 * Ordenação de elementos de diversos formatos
 */
class OrderFormat
{

    /**
     * Ordenação de formato JSON
     *
     * @param  String $json
     * @return highlight_string(str)
     */
    public function orderJSON($json = null) {
        $array = json_decode($json, true);
        $this->orderArray($array);

        return json_encode($array);
    }

    /**
     * Ordenação de Array
     *
     * @param  Array $array
     */
    private function orderArray(&$array = null) {
        ksort($array);
        foreach ($array as &$element) {
            if (gettype($element) == "array" && !empty($array)) {
                $this->orderArray($element);
            }
        }
    }
}
