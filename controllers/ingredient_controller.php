<?php

require_once __DIR__ . '/../models/ingredient_model.php';

class IngredientController {
    public function getAllIngredients() {
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
        require __DIR__ . '/../views/ingredient.php';
    }

    public function showAddIngredientForm() {
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
        
        require __DIR__ . '/../add_view/add_ingredient_view.php';
    }

    public function showEditIngredientForm($id) {

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

        require __DIR__ . '/../update_view/update_ingredient_view.php';
    }
}