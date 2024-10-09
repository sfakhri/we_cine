<?php

namespace App\Service;

use App\Service\Api\CallApi;

class Movies extends CallApi
{
    /**
     * Permet de recuerer les films qui sont affichés dans la home page
     * @return array|mixed
     */
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

    /**
     * Liste de film par catégorie
     * @param array|null $categoryId
     * @return array|mixed
     */
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
            return $data['results'];
        }
        return [];
    }

    /**
     * Detail d'un film donné
     * @param int|null $movieId
     * @return array
     */
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

    /**
     * Récupere la liste de videos disponible pour un film donné
     * @param string|null $movie
     * @return array|mixed
     */
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