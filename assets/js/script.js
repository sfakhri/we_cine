import $ from 'jquery';
import * as bootstrap from 'bootstrap';

$(function () {
    // autocomplete search film
    $('#search_suggestions').autocomplete({
        filterMinChars: 3,
    });
// search film by categories
    $('.jqCategorie').change(function () {
        // Vérifie si la case est cochée
        if ($(this).is(':checked')) {
            // Récupère toutes les catégories cochées
            let categories = [];
            $('.jqCategorie:checked').each(function () {
                categories.push($(this).val());
            });
            // Redirige vers l'URL avec les catégories en tant que paramètres de requête
            var url = 'https://127.0.0.1:8002/?categories=' + encodeURIComponent(categories.join(','));
            window.location.href = url;
        }
    });
    // show Movie Details
    $('.jqLinkMovie').on('click', function (e) {
        e.preventDefault();
        let movieId = $(this).data('movie-id');
        // console.log($(this).parent('.card-body').children('.star-ratings').html());
        let starRatingFilm = $(this).parent().parent().children('h5').children('.star-ratings').html();
        // Star Rating
        $('#myModalMovie .jqMovieRating').html(starRatingFilm)

        // Charge le contenu via AJAX
        $.ajax({
            url: 'https://127.0.0.1:8002/movieitem?item='+movieId,  // Remplace avec ton URL
            type: 'GET',
            success: function (data) {
                let dataMovie = data.data;
                let video = data.video;
                let vote_average = data.vote_average;
                vote_average = Math.floor(vote_average/2);
                let video_genres = '';
                console.log(dataMovie.genres);
                $.each(dataMovie.genres, function(index, value ) {
                    // Transformation du tableau
                    console.log(value.name);
                    video_genres += value.name+', ';
                });
                if(video_genres.length > 0) video_genres = video_genres.slice(0,-1);
                $('.jqMovieTitle').html(dataMovie.original_title+' Bande Annonce');
                $('.jqMovieRatingUsers').html(vote_average);
                $('.jqMovieRatingUsers').html('pour '+dataMovie.vote_count+' utilisateurs')
                $('.jqMovieGenre').html('Film: '+dataMovie.original_title)

            },
            error: function () {
                $('#modalContent').html('<p>Erreur lors du chargement du contenu.</p>');
            }
        });


        // console.log(movieId);

        // $('.jqModalTitle').html('Film Wolfs');

        let modalHtml = $('#myModalMovie').html();
        // Affiche la modale une fois le contenu chargé
        let myModal = new bootstrap.Modal(document.getElementById('myModalMovie'));
        myModal.show();
    });
});