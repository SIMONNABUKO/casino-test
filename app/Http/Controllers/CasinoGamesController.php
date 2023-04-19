<?php

namespace App\Http\Controllers;

use App\Models\CasinoGame;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CasinoGamesController extends Controller
{
    //
    const USER_NOT_FOUND = [
        "status" => "fail",
        "error" => "user_not_found"
    ];
    const NOT_ENOUGH_BALANCE = [
        "status" => "fail",
        "error" => "fail_balance"
    ];

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
    public function openGame(Request $request, $game)
    {
        $balance =auth()->user()->balance;
        $gameData = CasinoGame::find($game);
        $key = "DM9kcaPnLXSLRhxLAwqU9ojjK";
        $url = "https://test.rentalb.al/api/api-server";
        // dd($request->all());
        $response = Http::post($url, [
            'key' => $key,
            'cmd' => 'openGame',
            'gameId' => $gameData->game_id,
            'exitUrl' => 'https://casino.codingexpat.com/',
            'username' => auth()->user()->username,
            'balance'=>$balance,
            'demo'=>$request->demo
        ]);
        // dd($response);
        if ($response->successful()) {

            $responseBody = json_decode($response->body());
            // dd($responseBody);
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
            Log::info("API ERROR: " . $errorMessage);
            // dd($errorMessage);
        }
    }
    public function testGoldsvet(Request $request)
    {
        try {
            //code...
            Log::info("HTTP REQUEST RECEIVED");
            $data = $request->getContent();
            Log::info("DATA FROM THE GAME SERVER: ".$data);
            $decodedData = json_decode($data);

            $username = $decodedData->username;
            $user = User::where('username', $username)->first();
            if ($user) {
                $command = $decodedData->cmd;
                Log::info("COMMAND: ".$command);
                switch ($command) {
                    case 'getBalance':
                        $response_data = [
                            "status" => "success",
                            "error" => "",
                            "login" => $user->username,
                            "balance" => $user->balance, // Balance value must be returned in Data Type: Decimal 12,2
                            "currency" => "EUR", // Currency value must be returned in Data Type: String 3
                        ];
                        return response()->json($response_data);

                        break;

                    case 'writeBet':
                        $balance = $user->balance;
                        // Log::info('Balance:'.$balance);
                        // Log::info('Bet:'.$bet);
                        if ($balance < $decodedData->bet) {

                            // Log::info('Return an errror message balance < bet:'.$balance.' < '.$bet);
                            return response()->json($this::NOT_ENOUGH_BALANCE);
                        }
                        $new_balance = $balance - $decodedData->bet;
                        $final_balance = $new_balance + $decodedData->win;
                        $user->balance = $final_balance;
                        $user->save();
                        Log::info("BET: ".$decodedData->bet);
                        Log::info("WIN: ".$decodedData->win);
                        Log::info('user balance updated');
                        // Log::info('IgslotWrite:'. json_encode($ig_slot));
                        DB::commit();

                        $refreshUser = $user->fresh();

                        $response_data = [
                            "status" => "success",
                            "error" => "",
                            "login" => $user->username,
                            "balance" => $refreshUser->balance,
                            "currency" => "EUR",
                            "operationId" => "3234234"
                        ];
                        // Log::info('Response Data:'.json_encode($response_data));
                        return response()->json($response_data);

                        break;

                    default:
                        return response()->json($this::USER_NOT_FOUND);
                        break;
                }
            }
            return response()->json($this::USER_NOT_FOUND);
        } catch (\Throwable $th) {
            Log::error("Error from goldsvet: " . $th->getMessage());
            return response()->json([
                "status" => "error",
                "error" => $th->getMessage(),

            ]);
        }
    }
}
