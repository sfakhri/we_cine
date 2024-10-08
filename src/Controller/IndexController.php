<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Service\Categories;
use App\Service\Configuration;
use App\Service\Movies;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, Categories $categories, Movies $movies, Configuration $conf): Response
    {
        $listMovies = [];
        $searchCategories = $request->query->get('categories', '');
        $arr_searchCategories = [];
        if (!empty($searchCategories)) {
            $arr_searchCategories = explode(',', $searchCategories);
        }
        $form = $this->createForm(SearchType::class, null, ['action' => $this->generateUrl('app_home')]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $arr_searchCategories = [];
            $data = $form->getData();
            if (is_array($data) && array_key_exists('search_suggestions', $data)) {
                $listMovies = $movies->getMoviesSearch($data['search_suggestions']);
            }
        } else if (!empty($arr_searchCategories)) {
            $listMovies = $movies->getMoviesByCategory($arr_searchCategories);
        } else {

            $listMovies = $movies->getPopularMovies();
        }


        $conf->generateConfigurations();
        return $this->render('index/index.html.twig', [
            'categories' => $categories->getCategories(),
            'movies' => $listMovies,
            'baseUrlImages' => $conf->getSecureBaseUrl(),
            'form' => $form->createView(),
            'seachCategories' => $arr_searchCategories
        ]);
    }

    #[Route('/autocomplete', name: 'app_search_autocomplete')]
    public function autocomplete(Request $request, Movies $movie): Response
    {

        $search = $request->query->get('term', '');
        $movies = $movie->getMoviesSearch($search);
        $movies = array_column(array_slice($movies, 0, 5), 'title');
        return $this->json($movies);
    }

    #[Route('/movieitem', name: 'app_movieitem')]
    public function movieDetails(Request $request, Movies $movie): Response
    {
        $search = $request->query->get('item', '');
        $movies= ["data"=> [], "video"=>[]];

        $data = $movie->getMovieDetails($search);
        if(is_array($data))
        {
            if(array_key_exists('videos', $data)    && array_key_exists('results', $data['videos']))
            {
                $movies['video'] = $data['videos']['results'];
                unset($data['videos']);
            }
            $movies['data'] = $data;
        }
        return $this->json($movies);
    }
}