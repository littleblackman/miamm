<?php

namespace App\Services;

class ScraperService
{
    private $ch;

    private ?string $domain;

    private string $url;


    private array $config = [
                                'marmiton.org' => [
                                    'title' => '//h1',
                                    'time_total' => "//div[contains(@class, 'time__total')]/div",
                                    'time_preparation' => "//div[contains(@class, 'time__details')]/div[1]/div",
                                    'time_repos' => "//div[contains(@class, 'time__details')]/div[2]/div",
                                    'time_cuisson' => "//div[contains(@class, 'time__details')]/div[3]/div",
                                    'difficulty' => "//div[contains(@class, 'recipe-primary__item')]/span[contains(@class, 'icon-difficulty')]/following-sibling::span",
                                    'cost' => "//div[contains(@class, 'recipe-primary__item')]/span[contains(@class, 'icon-price')]/following-sibling::span",
                                    'ingredients' => "//div[@class='card-ingredient']",
                                    'ingredient_name' => ".//@data-name",
                                    'ingredient_quantity' => ".//span[@class='count']",
                                    'ingredient_unit' => ".//span[@class='unit']",
                                    'steps' => "//div[contains(@class, 'recipe-step-list__container')]//p"
                                ]
                                ,
                                'cuisineaz.com' => [
                                    'title' => '//h1[@class="recipe-title"]',
                                    'time_total' => "//span[contains(@class, 'recipe-time')]",
                                    'time_preparation' => "//div[contains(@class, 'preparation-time')]/span",
                                    'time_repos' => "//div[contains(@class, 'rest-time')]/span",
                                    'time_cuisson' => "//div[contains(@class, 'cooking-time')]/span",
                                    'difficulty' => "//div[contains(@class, 'recipe-difficulty')]",
                                    'cost' => "//div[contains(@class, 'recipe-cost')]",
                                    'ingredients' => "//ul[@class='ingredients-list']/li",
                                    'ingredient_name' => ".//span[@class='ingredient-name']",
                                    'ingredient_quantity' => ".//span[@class='ingredient-quantity']",
                                    'ingredient_unit' => ".//span[@class='ingredient-unit']",
                                    'steps' => "//div[@class='recipe-steps']/p"
                                ],
                                'ptitchef.com' => [
                                    'title' => '//h1[@class="title"]',
                                    'time_total' => "//div[contains(@class, 'rdbs-item')][contains(@title, 'Prêt en:')]/div[@class='rdbsi-val']",
                                    'time_preparation' => "//div[contains(@class, 'rdbs-item')][contains(@title, 'Préparation:')]/div[@class='rdbsi-val']",
                                    'time_repos' => "//div[contains(@class, 'rdbs-item')][contains(@title, 'Temps de repos:')]/div[@class='rdbsi-val']",
                                    'time_cuisson' => "//div[contains(@class, 'rdbs-item')][contains(@title, 'Cuisson:')]/div[@class='rdbsi-val']",
                                    'difficulty' => "//div[contains(@class, 'rdbs-item')][contains(@title, 'Difficulté:')]/div[@class='rdbsi-val']",
                                    'cost' => "",
                                    'ingredients' => "//ul[contains(@class, 'ingredients-ul')]/li",
                                    'ingredient_name' => ".//label",
                                    'ingredient_quantity' => "",
                                    'ingredient_unit' => "",
                                    'steps' => "//div[@class='rd-steps']//ul/li"
                                ]
    ];




    public function __construct()
    {
        $this->initCurl();
    }

    /**
     * Initialise cURL avec les options par défaut.
     */
    private function initCurl(): void
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 10);
    }


    public function getContent($url): ?array
    {
        $this->url = $url;
        $this->domain = $this->getDomain($url);
        if(!$html = $this->getHtml($url)) return null;

        if($html) {
            $html = $this->cleanHtml($html);
        }
        $data = [
            "title" => "",
            "time_total" => "",
            "time_preparation" => "",
            "time_repos" => "",
            "time_cuisson" => "",
            "difficulty" => "",
            "cost" => "",
            "ingredients" => [],
            "steps" => [],
            "site" => "",
            "origin_url" => ""
        ];

        // if is config domain
       if(array_key_exists($this->domain, $this->config)) {
            $data = $this->extractDataFromHtml($html, $this->config[$this->domain]);
        }

       // return html & data to Service
        return ['html' => $html, 'data' => $data];
    }

    public function getDomain($url): ?string
    {
        preg_match('/^(?:https?:\/\/)?(?:www\.)?([^\/]+)/i', $url, $matches);
        return $matches[1] ?? null;
    }

    public function getHtml($url): ?string
    {

        curl_setopt($this->ch, CURLOPT_URL, $url);
        $html = curl_exec($this->ch);
        if ($html === false) {
            echo "Erreur cURL : " . curl_error($this->ch);
        }
        $httpCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        if ($httpCode !== 200 || !$html) {
            return null;
        }
        return $html;
    }

    private function cleanHtml(string $html): string
    {
        // Supprime head, scripts, styles, meta, links et commentaires
        $html = preg_replace('/<head.*?>.*?<\/head>/is', '', $html);
        $html = preg_replace('/<script.*?>.*?<\/script>/is', '', $html);
        $html = preg_replace('/<style.*?>.*?<\/style>/is', '', $html);
        $html = preg_replace('/<meta.*?>/is', '', $html);
        $html = preg_replace('/<link.*?>/is', '', $html);
        $html = preg_replace('/<!--.*?-->/is', '', $html);

        // Supprime les balises de tracking et Google Tag Manager
        $html = preg_replace('/<noscript.*?>.*?<\/noscript>/is', '', $html);
        $html = preg_replace('/<iframe.*?>.*?<\/iframe>/is', '', $html);

        // Supprime les sections non pertinentes
        $html = preg_replace('/<nav.*?>.*?<\/nav>/is', '', $html);  // Navigation
        $html = preg_replace('/<header.*?>.*?<\/header>/is', '', $html); // En-tête
        $html = preg_replace('/<footer.*?>.*?<\/footer>/is', '', $html); // Pied de page
        $html = preg_replace('/<aside.*?>.*?<\/aside>/is', '', $html); // Barres latérales
        $html = preg_replace('/<div class="(mrtn-header|mrtn-footer|ad-container|recipe-buttons-container|mrtn-sidebar).*?>.*?<\/div>/is', '', $html); // Conteneurs pub et menus

        // Supprime les icônes et boutons de partage
        $html = preg_replace('/<div class="recipe-buttons-share-modal.*?>.*?<\/div>/is', '', $html);
        $html = preg_replace('/<div class="footer-social-media.*?>.*?<\/div>/is', '', $html);
        $html = preg_replace('/<button.*?>.*?<\/button>/is', '', $html);

        // Supprime les publicités et recommandations
        $html = preg_replace('/<div class="recipe-like-too.*?>.*?<\/div>/is', '', $html);
        $html = preg_replace('/<div class="seo-links-tag-page.*?>.*?<\/div>/is', '', $html);
        $html = preg_replace('/<div class="mrtn-home-recipe-card.*?>.*?<\/div>/is', '', $html);
        $html = preg_replace('/<div class="mrtn-modal.*?>.*?<\/div>/is', '', $html);

        // Supprime les commentaires et avis
        $html = preg_replace('/<div class="recipe-reviews-list.*?>.*?<\/div>/is', '', $html);
        $html = preg_replace('/<div class="recipe-reviews-list__review.*?>.*?<\/div>/is', '', $html);
        $html = preg_replace('/<div class="recipe-reviews-list__rate-card.*?>.*?<\/div>/is', '', $html);

        // Supprime la newsletter et l'abonnement au magazine
        $html = preg_replace('/<div class="recipe-newsletter.*?>.*?<\/div>/is', '', $html);
        $html = preg_replace('/<div class="magazine-autopromo.*?>.*?<\/div>/is', '', $html);

        // Nettoyage des espaces superflus
        $html = preg_replace('/\s+/', ' ', $html);
        $html = preg_replace('/>\s+</', '><', $html); // Réduit les espaces entre balises

        return trim($html);
    }


    public function extractDataFromHtml(string $html, array $siteConfig): array
    {
        libxml_use_internal_errors(true);
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $html = preg_replace('/&(?!amp;|lt;|gt;|quot;|#039;|\w+;)/', '&amp;', $html);

        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);

        // Fonction pour récupérer et nettoyer un élément XPath
        $safeQuery = function ($query) use ($xpath) {
            $nodes = $xpath->query($query);
            return ($nodes && $nodes->length > 0) ? trim($nodes->item(0)->nodeValue) : '';
        };

        // Récupérer les différentes données avec sécurisation
        $title = $safeQuery($siteConfig['title']);
        $time_total = $safeQuery($siteConfig['time_total']);
        $time_preparation = $safeQuery($siteConfig['time_preparation']);
        $time_repos = $safeQuery($siteConfig['time_repos']);
        $time_cuisson = $safeQuery($siteConfig['time_cuisson']);
        $difficulty = $safeQuery($siteConfig['difficulty']);
        $cost = $safeQuery($siteConfig['cost']);

        // Récupérer les ingrédients
        $ingredients = [];
        $ingredientNodes = $xpath->query($siteConfig['ingredients']);
        if ($ingredientNodes && $ingredientNodes->length > 0) {
            foreach ($ingredientNodes as $ingredientNode) {
                $name = $safeQuery($siteConfig['ingredient_name']);
                $quantity = $safeQuery($siteConfig['ingredient_quantity']);
                $unit = $safeQuery($siteConfig['ingredient_unit']);

                $ingredients[] = [
                    'name' => $name,
                    'quantity' => trim("$quantity $unit")
                ];
            }
        }

        // Récupérer les étapes
        $steps = [];
        $stepNodes = $xpath->query($siteConfig['steps']);
        if ($stepNodes && $stepNodes->length > 0) {
            foreach ($stepNodes as $stepNode) {
                $steps[] = trim($stepNode->nodeValue);
            }
        }

        return [
            'title' => $title,
            'time_total' => $time_total,
            'time_preparation' => $time_preparation,
            'time_repos' => $time_repos,
            'time_cuisson' => $time_cuisson,
            'difficulty' => $difficulty,
            'cost' => $cost,
            'ingredients' => $ingredients,
            'steps' => $steps,
            'site' => $this->domain,
            'origin_url' => $this->url
        ];
    }




}