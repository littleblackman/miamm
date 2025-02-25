<?php

namespace App\Services;

class ScraperService
{
    private $ch;

    private ?string $domain;


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


    public function getContent($url): array
    {
        $this->domain = $this->getDomain($url);
        $html = $this->getHtml($url);
        $html = $this->cleanHtml($html);
        $data = [];

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
        // Supprime head, scripts, styles et meta
        $html = preg_replace('/<head.*?>.*?<\/head>/is', '', $html);
        $html = preg_replace('/<script.*?>.*?<\/script>/is', '', $html);
        $html = preg_replace('/<style.*?>.*?<\/style>/is', '', $html);
        $html = preg_replace('/<meta.*?>/is', '', $html);
        $html = preg_replace('/<link.*?>/is', '', $html);
        $html = preg_replace('/<!--.*?-->/is', '', $html);

        // Supprime les espaces inutiles
        $html = preg_replace('/\s+/', ' ', $html);

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
            'steps' => $steps
        ];
    }




}