<?php

require_once __DIR__ . '/../models/user_model.php';

class UserController
{
    public function getAllUsers()
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
        require __DIR__ . '/../views/user.php';
    }

    public function showEditUserForm($id)
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

        require __DIR__ . '/../update_view/update_user_view.php';
    }
}
