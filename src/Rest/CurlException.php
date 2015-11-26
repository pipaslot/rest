<?php

namespace Pipas\Rest;

/**
 * Vyjímka sloužící jako oznámení o pádu, jež by měli vývojáři vyřešit a vhodně ošetřit. Nepoporučuje se ji zachytávat za běhu programu.
 *
 * @author Petr Štipek <p.stipek@email.cz>
 */
class CurlException extends \Exception
{

    private $info;
    private $url;
    private $options;
    private $response;

    public function __construct($message, array $options, array $curlinfo, $response = null)
    {
        $this->message = $message;
        $this->url = $curlinfo['url'];
        $this->setOptions($options);
        $this->setResponse($response);
        $this->info = $curlinfo;
    }

    function getInfo()
    {
        return $this->info;
    }

    /**
     * @return string
     */
    function getUrl()
    {
        return $this->url;
    }

    /**
     * @return array
     */
    function getOptions()
    {
        return $this->options;
    }

    /**
     *
     * @return mixed
     */
    function getResponse()
    {
        return $this->response;
    }

    private function setOptions(array $options)
    {
        if (isset($options[CURLOPT_POSTFIELDS])) {
            $options[CURLOPT_POSTFIELDS] = json_decode($options[CURLOPT_POSTFIELDS]);
        }
        $this->options = $this->translateCurlOptionKeys($options);
    }

    private function setResponse($response)
    {

        if (isset($response['data']) AND is_string($response['data'])) $response['data'] = json_decode($response['data']);
        $this->response = $response;
    }

    /**
     * Přeloží klíče pro nastavení CURL voleb z hodnot konstant do názvu konstant aby se volby daly číst lidmi
     * @param array $options
     * @return array
     */
    private function translateCurlOptionKeys(array $options)
    {
        $const = get_defined_constants(true)['curl'];
        $newOptions = array();
        foreach ($options as $key => $value) {
            $name = array_search($key, $const);
            if ($name === false) {
                $newOptions[$key] = $value;
            } else {
                $newOptions[$name] = $value;
            }
        }
        return $newOptions;
    }

}
