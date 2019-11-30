<?php
namespace App;

use Symfony\Component\HttpFoundation\Response;

class RecipeController
{
    public function lunch()
    {
        // INITIAL VARIABLE
        $base_url = __DIR__;
        $recipe_dir = $base_url."/Recipe/";
        $ingredient_dir = $base_url."/Ingredient/";

        $ingredients = $this->read_file($ingredient_dir, 'data.json');
        $recipes = $this->read_file($recipe_dir, 'data.json');

        // TODAY
        $today = date("Y-m-d");
        // $today = date('Y-m-d', strtotime("-350 days"));
        // echo 'today = '.$today.'<br>';

        return $this->recipe_available($today, $ingredients, $recipes);
    }
    public function read_file($path, $file_name)
    {
        $list = [];
        if (is_dir($path) && file_exists($path.$file_name)) {
            $file = file_get_contents($path.$file_name);
            $list = json_decode($file, true);
        }
        return $list;
    }
    public function recipe_available($today, $ingredients, $recipes)
    {
        $data = [];
        try {
            // INITIAL VARIABLE
            if (count($ingredients) > 0) {
                $ingredients = $ingredients['ingredients'];
            }
            if (count($recipes) > 0) {
                $recipes = $recipes['recipes'];
            }

            $ingredient_available = [];
            $recipe_available = [];
            $recipe_sort = [];

            // FILTER AVAILABLE INGREDIENT
            foreach ($ingredients as $ingredient) {
                if ($today < $ingredient['use-by'] && $ingredient['best-before'] < $ingredient['use-by']) {
                    $ingredient_available[$ingredient['title']] = $ingredient['use-by'];
                }
            }

            // FILTER AVAILABLE RECIPE
            foreach ($recipes as $recipe) {
                $available = true;
                $use_by = null;

                foreach ($recipe['ingredients'] as $ingredient) {
                    if (!array_key_exists($ingredient, $ingredient_available)) {
                        $available = false;
                        break;
                    } else {
                        if ($use_by == null || $use_by > $ingredient_available[$ingredient]) {
                            $use_by = $ingredient_available[$ingredient];
                        }
                    }
                }
                if ($available) {
                    $recipe_sort[$recipe['title']] = $use_by;
                    $recipe_available[$recipe['title']] = $recipe;
                }
            }

            //HANDLE RESPONSE DATA
            arsort($recipe_sort);
            foreach ($recipe_sort as $key=>$val) {
                array_push($data, $recipe_available[$key]);
            }
        } catch (Exception $e) {
            echo 'Message: ' .$e->getMessage();
        } finally {
            return new Response(json_encode(['recipe' => $data]));
        }
    }
}
