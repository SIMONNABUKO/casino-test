<?php

namespace App\Http\Controllers;

use App\Models\CasinoGame;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CasinoGamesController extends Controller
{
    //
    public function updateGames()
    {
        $key = env("CASINO_KEY");
        $url = env("CASINO_SERVER_URL");
        $response = Http::post($url, [
            'key' => $key,
            'cmd' => 'getCategories'
        ]);


        if ($response->successful()) {
            // The request was successful and the response body can be accessed as a string
            $responseBody = $response->body();
            $data = json_decode($responseBody);
            $casino_categories = $data->data;
            foreach ($casino_categories as $categoryData) {

                $category = new Category();
                $category->title = $categoryData->title;
                $category->real_id = $categoryData->id;
                $category->href = $categoryData->href;
                $category->save();

                foreach ($categoryData->category_games as $gameData) {
                    $casino_game = new CasinoGame();
                    $casino_game->game_id = $gameData->pivot->game_id;
                    $casino_game->category_id = $gameData->pivot->category_id;
                    $casino_game->name = $gameData->name;
                    $casino_game->category = $categoryData->title;
                    $casino_game->title = $gameData->title;
                    $casino_game->gamebank = $gameData->gamebank;
                    $casino_game->device = $gameData->device;
                    $casino_game->label = $gameData->label;
                    $casino_game->shop_id = $gameData->shop_id;
                    $casino_game->bet = $gameData->bet;
                    $casino_game->original_id = $gameData->original_id;
                    $casino_game->save();
                }
            }

            dd("DONE!");
        } else {
            // The request returned an error (e.g. 404 or 500 status code)
            $errorMessage = $response->status() . ' ' . $response->body();
            dd($errorMessage);
        }
    }
    public function home()
    {
        $game_categories = Category::get();
        $games = CasinoGame::inRandomOrder()->with('category')->get();
        return view('welcome', compact('game_categories', 'games'));
    }
    public function openGame($game)
    {
        $gameData = CasinoGame::find($game);
        $key = env("CASINO_KEY");
        $url = env("CASINO_SERVER_URL");
        $response = Http::post($url, [
            'key' => $key,
            'cmd' => 'openGame',
            'gameId' => $gameData->game_id,
            'exitUrl' => 'http://127.0.0.1:8000/',
            'username'=>'SimonAngatia'
        ]);
        // dd($response);
        if ($response->successful()) {
            $responseBody = json_decode($response->body());
            $headers = $response->headers();
            // dd($responseBody);
            // dd($headers);
            $gameData = $responseBody->data;
            // dd($gameData);
            // return redirect($gameData->url);
            return view('game', compact('gameData'));
        } else {
            // The request returned an error (e.g. 404 or 500 status code)
            $errorMessage = $response->status() . ' ' . $response->body();
            Log::info("API ERROR: ".$errorMessage);
            // dd($errorMessage);
        }
    }
}
