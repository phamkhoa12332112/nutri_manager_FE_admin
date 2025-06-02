<?php

require_once __DIR__ . '/../models/mood_model.php';

class MoodController
{
    public function getAllMoods()
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
        require __DIR__ . '/../views/mood.php';
    }

    public function showAddMoodForm()
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

        require __DIR__ . '/../add_view/add_mood_view.php';
    }

    public function showEditMoodForm($id)
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

        require __DIR__ . '/../update_view/update_mood_view.php';
    }
}