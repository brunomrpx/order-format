<?php
namespace app;

/**
 * Ordenação de elementos de diversos formatos
 */
class OrderFormat
{

    /**
     * Opções de ordenação
     * @var Array
     */
    public $options = [
        'removeEmptyElements' => false
    ];

    public function __construct(Array $options = null) {
        if ($options !== null) {
            $this->options['removeEmptyElements'] = isset($options['removeEmptyElements']) ? $options['removeEmptyElements'] : $this->options['removeEmptyElements'];
        }
    }

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
        foreach ($array as $key => &$element) {
            if (gettype($element) == "array" && !empty($array)) {
                $this->orderArray($element);
            } else {
                if ($this->options['removeEmptyElements'] && $element === "") {
                    unset($array[$key]);
                }
            }
        }
    }
}
