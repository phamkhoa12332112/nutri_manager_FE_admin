<?php

require_once __DIR__ . '/../models/recipe_model.php';

class RecipeController
{
    public function getAllRecipes()
    {
        $userModel = new UserModel();
        $users = $userModel->getAllUsers();

        $mealModel = new MealModel();
        $meals = $mealModel->getAllMeals();

        $recipeModel = new RecipeModel();
        $recipes = $recipeModel->getAllRecipes();
        $ingredientModel = new IngredientModel();
        $ingredients = $ingredientModel->getAllIngredients();

        $moodModel = new MoodModel();
        $moods = $moodModel->getAllMoods();
        require __DIR__ . '/../views/recipe.php';
    }

    public function showAddRecipeForm()
    {
        $userModel = new UserModel();
        $users = $userModel->getAllUsers();

        $mealModel = new MealModel();
        $meals = $mealModel->getAllMeals();

        $recipeModel = new RecipeModel();
        $recipes = $recipeModel->getAllRecipes();
        $ingredientModel = new IngredientModel();
        $ingredients = $ingredientModel->getAllIngredients();

        $moodModel = new MoodModel();
        $moods = $moodModel->getAllMoods();

        require __DIR__ . '/../add_view/add_recipe_view.php';
    }

    public function showEditRecipeForm($id)
    {
        $recipeModel = new RecipeModel();
        $recipes = $recipeModel->getAllRecipes();

        $userModel = new UserModel();
        $users = $userModel->getAllUsers();

        $mealModel = new MealModel();
        $meals = $mealModel->getAllMeals();

        $ingredientModel = new IngredientModel();
        $ingredients = $ingredientModel->getAllIngredients();
        
        $moodModel = new MoodModel();
        $moods = $moodModel->getAllMoods();

        require __DIR__ . '/../update_view/update_recipe_view.php';
    }

    public function showSetMoodForm($id)
    {
        $recipeModel = new RecipeModel();
        $recipes = $recipeModel->getAllRecipes();
        $recipeByIds = $recipeModel->getDetailsById($id);
        $recipeMoodMeals = $recipeModel->getRecipeMood($id);

        $userModel = new UserModel();
        $users = $userModel->getAllUsers();

        $mealModel = new MealModel();
        $meals = $mealModel->getAllMeals();

        $ingredientModel = new IngredientModel();
        $ingredients = $ingredientModel->getAllIngredients();

        $moodModel = new MoodModel();
        $moods = $moodModel->getAllMoods();

        require __DIR__ . '/../update_view/update_recipe_mood_view.php';
    }
}