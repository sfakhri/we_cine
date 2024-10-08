<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RatingExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // Déclare un filtre 'rating' qui utilisera la fonction transformRating()
            new TwigFilter('rating', [$this, 'transformRating'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Convertit un nombre en étoiles (rating)
     */
    public function transformRating(int $rating, int $maxRating = 5): string
    {
        $fullStars = min($rating, $maxRating); // Nombre d'étoiles pleines
        $emptyStars = $maxRating - $fullStars; // Nombre d'étoiles vides

        $stars = str_repeat('★', $fullStars);  // Étoiles pleines
        $stars .= str_repeat('☆', $emptyStars); // Étoiles vides

        return $stars;
    }
}