<?php

require_once __DIR__ . '/../models/meal_model.php';

class MealController
{
    public function getAllMeals()
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
        require __DIR__ . '/../views/meal.php';
    }


    public function showAddMealForm()
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

        require __DIR__ . '/../add_view/add_meal_view.php';
    }

    public function showEditMealForm($id)
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

        require __DIR__ . '/../update_view/update_meal_view.php';
    }
}