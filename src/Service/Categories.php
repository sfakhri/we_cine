<?php

namespace App\Service;

use App\Service\Api\CallApi;

class Categories extends CallApi
{
    public function getCategories()
    {
        $query = ["language" => "fr"];
        $apiPartial = "/genre/movie/list";
        $data = $this->generate($query, $apiPartial);
        if(!empty($data)    && is_array($data)  && array_key_exists('genres', $data))
        {
            return $data['genres'];
        }
        return [];
    }
}