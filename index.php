<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/controllers/meal_controller.php';
require_once __DIR__ . '/controllers/user_controller.php';
require_once __DIR__ . '/controllers/recipe_controller.php';
require_once __DIR__ . '/controllers/ingredient_controller.php';
require_once __DIR__ . '/controllers/mood_controller.php';

$mealController = new MealController();
$userController = new UserController();
$recipeController = new RecipeController();
$ingredientController = new IngredientController();
$moodController = new MoodController();

$action = isset($_GET['action']) ? $_GET['action'] : 'users';

switch ($action) {
    // Meal routes
    case 'meals':
        $mealController->getAllMeals();
        break;

    // User routes
    case 'users':
        $userController->getAllUsers();
        break;

    // Recipe routes
    case 'recipes':
        $recipeController->getAllRecipes();
        break;

    // Ingredient routes
    case 'ingredients':
        $ingredientController->getAllIngredients();
        break;

    // Mood routes
    case 'moods':
        $moodController->getAllMoods();
        break;

    // Add Meal Form
    case 'add_meal':
        $mealController->showAddMealForm();
        break;

    // Add Mood Form
    case 'add_mood':
        $moodController->showAddMoodForm();
        break;

    // Add Recipe Form
    case 'add_recipe':
        $recipeController->showAddRecipeForm();
        break;

    // Add Ingredient Form
    case 'add_ingredient':
        $ingredientController->showAddIngredientForm();
        break;

    // Update Meal Form
    case 'update_meal':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $mealController->showEditMealForm($id);
        break;

    // Update Mood Form
    case 'update_mood':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $moodController->showEditMoodForm($id);
        break;

    // Update User Form
    case 'update_user':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $userController->showEditUserForm($id);
        break;
    
    // Update Recipe Form
    case 'update_recipe':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $recipeController->showEditRecipeForm($id);
        break;

    // Update Ingredient Form
    case 'update_ingredient':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $ingredientController->showEditIngredientForm($id);
        break;

    // Set Mood Form
    case 'set_mood':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $recipeController->showSetMoodForm($id);
        break;
    default:
        echo "Page not found.";
        break;
}
