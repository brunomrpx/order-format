<?php

namespace app;

include("lib/array2xml.php");

use app\lib\Array2XML;


header("Content-Type: text/html; charset=utf8");
session_start();

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['format-content'])) {
                $removeEmptyElements = isset($_POST['removeEmptyElements']);
                $formatContent = $_POST['format-content'];
                $order = strtoupper($_POST['order']);
                $orderFormat = new OrderFormat([
                    'removeEmptyElements' => $removeEmptyElements,
                    'order' => $order
                ]);
                $result = $orderFormat->order($formatContent);
            }
    }
} catch (\Exception $exception) {
    $result = $exception->getMessage();
} finally {
    $_SESSION['result'] = $result;
    header("Location: ../index.php");
}

/**
 * Ordenação de elementos de diversos formatos
 */
class OrderFormat {

    /**
     * Opções de ordenação
     *
     * @var Array
     */
    private $options = [
        'removeEmptyElements' => false,
        'order' => 'ASC'
    ];

    /**
     * Instancia o objeto
     *
     * @param Array $options
     */
    public function __construct(Array $options = null) {
        $this->verifyOptions($options);
    }

    /**
     * Atribui as opções escolhidas
     *
     * @param  Array $options
     */
    private function verifyOptions($options) {
        if (isset($options)) {
            foreach ($options as $key => $value) {
                $this->options[$key] = $value;
            }
        }
    }

    /**
     * Busca o formato dos dados de uma string
     *
     * @param  String $formatString
     * @return String
     */
    private function getFormat($formatString = null) {
       $format = null;
       if ($this->isJson($formatString)) {
            $format = "JSON";
       } else if ($this->isXml($formatString)) {
            $format = "XML";
       }

       if (!isset($format)) {
            throw new \Exception("Input inválido ou formato não suportado.");
       }

       return $format;
    }

    /**
     * Verifica se uma String é do formato XML
     *
     * @param  String  $string
     * @return boolean
     */
    private function isXml($string) {
        return is_object(simplexml_load_string($string));
    }

    /**
     * Verificar se uma String é do formato JSON
     *
     * @param  String  $string [description]
     * @return boolean
     */
    private function isJson($string) {
        return (json_decode($string) !== null);
    }

    /**
     * Recebe o conteúdo e realiza a ordenação com base nas opções
     *
     * @param  String $formatString
     * @return String
     */
    public function order($formatString = null) {
        $content = $this->getContent($formatString);
        $format = $this->getFormat($content);
        $orderedContent = null;

        switch ($format) {
            case 'JSON':
                $orderedContent = $this->orderJSON($content);
                break;
            case 'XML':
                $orderedContent = $this->orderXML($content);
                break;
            default:
                throw new \Exception("Formato inválido");
                break;
        }

        return htmlentities($orderedContent);
    }

    /**
     * Busca o conteúdo do input enviado
     *
     * @param  String $formatString
     * @return String
     */
    private function getContent($formatString) {
         if (!isset($formatString)) {
            throw new \Exception("Conteúdo não informado");
        }

        $content = $formatString;
        // Verifica se é uma URL e busca o conteúdo
        $isURL = filter_var($formatString, FILTER_VALIDATE_URL);
        if ($isURL !== false) {
            $content = file_get_contents($formatString);
        }

        return $content;
    }

    /**
     * Ordenação de formato XML
     *
     * @param  String $content
     * @return String
     */
    private function orderXML($content) {
        $xml = simplexml_load_string($content);
        $array = json_decode(json_encode($xml), true);
        $this->orderArray($array);
        $xml = Array2XML::createXML('XML', $array);

        return $xml->saveXML();
    }

    /**
     * Ordenação de formato JSON
     *
     * @param  String $json
     * @return String
     */
    private function orderJSON($json = null) {
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
        if ($this->options['order'] === 'ASC') {
            ksort($array);
        } else if ($this->options['order'] === 'DESC') {
            krsort($array);
        }
        foreach ($array as $key => &$element) {
            if (gettype($element) == "array" && !empty($array)) {
                $this->orderArray($element);
            }

            if ($this->options['removeEmptyElements'] ) {
                if ($element === "" || is_null($element) || (gettype($element) == "array" && empty($element))) {
                    unset($array[$key]);
                }
            }
        }
    }
}
