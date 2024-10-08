<?php

namespace App\Service;

use App\Service\Api\CallApi;

class Movies extends CallApi
{
    public function getPopularMovies()
    {
        $query = [
            "language" => "fr",
            "region" => "FR",
            "page" => 1
        ];
        $apiPartial = "/movie/popular";
        $data = $this->generate($query, $apiPartial);
        if (!empty($data) && is_array($data) && array_key_exists('results', $data)) {
            return $data['results'];
        }
        return [];
    }

    public function getMoviesByCategory(?array $categoryId = [])
    {
        $query = [
            "with_genres" => implode(",", $categoryId),
            "language" => "fr",
            "region" => "FR",
            "page" => 1
        ];
        $apiPartial = "/discover/movie";
        $data = $this->generate($query, $apiPartial);
        if (!empty($data) && is_array($data) && array_key_exists('results', $data)) {
            dump($data);
            return $data['results'];
        }
        return [];
    }

    public function getMovieDetails(?int $movieId = 0)
    {
        $query = ["api_key" => $this->getApiKey(), "append_to_response"=> "videos"];
        $apiPartial = '/movie/'.$movieId.'?api_key='.$this->getApiKey();
        $data = $this->generate($query, $apiPartial);
        if (!empty($data) && is_array($data) ) {
            return $data;
        }
        return [];
    }

    public function getMoviesSearch(?string $movie = '')
    {
        $query = [
            "query" => $movie,
            "language" => "fr",
            "region" => "FR",
            "page" => 1,
            "include_adult" => false,
        ];
        $apiPartial = "/search/movie";
        $data = $this->generate($query, $apiPartial);
        if (!empty($data) && is_array($data) && array_key_exists('results', $data)) {
            return $data['results'];
        }
        return [];
    }
}