<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\GameStage;
use App\Models\User;
use App\Service\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class GameController extends Controller
{
    public function getCurrentGame(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $game = $user->player->game;
        if (!$game) {
            return response()->json(null);
        }
        $game->load('stages');

        return response()->json(GameResource::make($game));
    }

    public function newGame(array $players)
    {
        /** @var User $user */
        $user = Auth::user();
        $game = $user->startNewGame($players);
        $game->load('stages');
        return response()->json(GameResource::make($game));
    }
//
//    public function getTextQuestion(GameStage $stage)
//    {
//        $question = $stage->questions()
//            ->whereNull('is_correct')
//            ->orderBy('id')
//            ->first();
//
//        if (null === $question) {
//            return response()->json(["error" => "No questions left in stage"], 422);
//        }
//
//        if (!$question->started_at) {
//            $question->started_at = now();
//            $question->save();
//        }
//
//        return response()->json(TextQuestionResource::make($question));
//    }
//
//    public function answerTextQuestion(Request $request, GameStageQuestion $question)
//    {
//        $answer = $request->input('answer');
//        $question->is_correct = $answer === $question->question->is_correct;
//        $question->finished_at = now();
//        $question->duration = $question->started_at->diffInSeconds($question->finished_at);
//        $question->save();
//
//        if ($question->is_correct) {
//            $question->stage->score++;
//            if ($question->stage->score >= 3) {
//                $question->stage->is_code_part_provided = true;
//            }
//        }
//
//        $question->stage->duration += $question->duration;
//        $question->stage->save();
//        return response()->json(TextQuestionResource::make($question));
//    }
//
//    public function getPasswordQuestion()
//    {
//        /** @var User $user */
//        $user = Auth::user();
//        $game = $user->game;
//        $game->load('stages');
//        if (!$game->password_started_at) {
//            $game->password_started_at = now();
//        }
//        $codes = $game->stages()->where('is_code_part_provided', true)->count();
//        $game->save();
//
//        if ($codes === 4) {
//            $game->finishGame();
//        }
//
//        $game->password_question = $game->passwordQuestion()->first();
//
//        return response()->json(PasswordQuestionResource::make($game));
//    }
//
//    public function answerPasswordQuestion(Request $request)
//    {
//        /** @var User $user */
//        $user = Auth::user();
//        $game = $user->game;
//        $password = $game->passwordQuestion;
//        $code = strtoupper($request->input('code'));
//        $answerCodes = $code;
//        if (!$game->finished_at) {
//            $game->password_tries++;
//            $game->save();
//            if ($code === strtoupper($password->code)) {
//                $game = $game->finishGame();
//                $game->correct_code = true;
//            }
//        }
//        $stages = $game->stages()->orderBy('serial')->get();
//        $codes = [];
//        foreach ($stages as $i => $stage) {
//            $codes[] = ($stage->is_code_part_provided || $answerCodes[$i] === strtoupper($stage->code_part)) ? $stage->code_part : null;
//        }
//        $result = [
//            'codes' => $codes,
//            'result' => !!$game->finished_at,
//        ];
//        return response()->json($result);
//    }

    public function getScoreboard(Request $request)
    {
        $limit = $request->query('limit') ?? '10';
        $gamesRating = Game::getTop($limit);

        return response()->json(["collection" => $gamesRating]);
    }
}
