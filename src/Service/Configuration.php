<?php

namespace App\Service;

use App\Service\Api\CallApi;

class Configuration extends CallApi
{
    private $baseUrl = "";
    private $secureBaseUrl = "";

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(?string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    public function getSecureBaseUrl(): ?string
    {
        return $this->secureBaseUrl;
    }

    public function setSecureBaseUrl(?string $secureBaseUrl): void
    {
        $this->secureBaseUrl = $secureBaseUrl;
    }

    /**
     * Permet de générer les données de configuration
     * @return void
     */
    public function generateConfigurations()
    {
        $query = [];
        $apiPartial = "/configuration";
        $data = $this->generate($query, $apiPartial);
        if(is_array($data)  && array_key_exists('images', $data))
        {
            if(array_key_exists('base_url', $data['images'])){
                $this->setBaseUrl($data['images']['base_url']);
            }
            if(array_key_exists('secure_base_url', $data['images'])){
                $this->setSecureBaseUrl($data['images']['secure_base_url']);
            }

        }
    }

}