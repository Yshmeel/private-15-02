<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\SelectRoomRequest;
use App\Http\Requests\SendMessageRequest;
use App\Models\ChatMessage;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;

class ApiController extends Controller
{
    public function createUser(CreateUserRequest $request) {
        $rooms = Room::query()
            ->with(['users'])
            ->get();

        $randomRoom = null;

        $roomsWithFreeSpace = [];

        foreach($rooms as $room) {
            // if current room exceeds limits of max users and do not have enough space to *BREATH*
            if(count($room->users) >= $room->max_users) {
                continue;
            }

            $roomsWithFreeSpace[] = $room;

            if(rand(0, 3) == 2) {
                $randomRoom = $room;
                break;
            }
        }

        // fallback if russian roulette did not work (i mean rand(0, 3))
        if($randomRoom == null) {
            $randomRoom = $roomsWithFreeSpace[0];
        }

        $user = User::create([
            'name' => $request->input('name'),
            'avatar_id' => $request->input('avatar_id'),
            'selected_room_id' => $randomRoom->id,
            'x' => rand($randomRoom->x, ($randomRoom->x + $randomRoom->width) - 48),
            'y' => rand($randomRoom->y - 48, ($randomRoom->y + $randomRoom->height) - 48),
            'last_updated_at' => Carbon::now(),
        ]);

        // sanctum for tokens??
        $token = $user->createToken('temp');
        return [
            'token' => $token->plainTextToken,
        ];
    }

    public function routine() {
        $user = auth()->user();

        $rooms = Room::query()->with(['users'])->get();
        $currentRoom = Room::query()
            ->with(['messages', 'users', 'messages.user'])
            ->where('id', $user->selected_room_id)->first();

        // update current user's last_updated_at date to not delete him, as we see, that he stays on page
        $user->last_updated_at = Carbon::now();
        $user->save();

        $users = User::all();

        // to be configured in config/app.php
        $deletingUsers = User::query()
            ->where('last_updated_at', '<', Carbon::now()->subSeconds(config('user_timeout')));

        // delete chat messages from user room if he is the only one in room
        foreach($deletingUsers as $user) {
            foreach($rooms as $room) {
                // users count is count of $room->users, and we double check users count
                if($room->id == $user->selected_room_id && count($room->users) <= 1) {
                    ChatMessage::query()->where('room_id', $room->id)->delete();
                }
            }
        }

        return [
            'user' => auth()->user(),
            'users' => $users,
            'rooms' => $rooms->map(function($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'max_users' => $room->max_users,
                    'users_count' => count($room->users),
                    'users' => $room->users,
                    'x' => $room->x,
                    'y' => $room->y,
                    'width' => $room->width,
                    'height' => $room->height,
                    'layer' => $room->layer,
                ];
            }),
            'messages' => $currentRoom->messages->map(function($message) {
                return [
                    'message' => $message->message,
                    'user' => $message->user,
                    'created_at' => $message->created_at,
                ];
            }),
            'current_room' => $currentRoom,
        ];
    }

    public function logout() {
        $user = auth()->user();
        $room = Room::query()
            ->with(['users'])
            ->where('id', $user->selected_room_id)->first();

        // delete user from database, it equals to logout process
        $user->delete();

        // delete chat messages if there's noone in room
        if(count($room->users) <= 1) {
            ChatMessage::query()->where('room_id', $room->id)->delete();
        }

        return response()->noContent();
    }

    public function sendMessage(SendMessageRequest $request) {
        $user = auth()->user();

        ChatMessage::create([
            'room_id' => $user->selected_room_id,
            'user_id' => $user->id,
            'message' => $request->input('message'),
            'created_at' => Carbon::now()
        ]);

        return response()->noContent();
    }

    public function selectRoom(SelectRoomRequest $request) {
        $user = auth()->user();
        $oldRoom = Room::query()
            ->with(['users'])
            ->where('id', $user->selected_room_id)->first();

        $newRoom = Room::query()
            ->with(['users'])
            ->where('id', $request->input('room_id'))->first();

        if($newRoom == null) {
            return response()->json([
                'type' => '##/error/room-not-found',
            ], 404);
        }

        // if exceeds limits of users count in rooms
        if((count($newRoom->users) + 1) > $newRoom->max_users) {
            return response()->json([
                'type' => '##/error/exceeded-limit',
            ], 499);
        }

        $x = $request->input('x');
        $y = $request->input('y');

        // change user's selected room id
        $user->selected_room_id = $request->input('room_id');
        $user->x = $x;
        $user->y = $y;
        $user->save();

        // delete chat messages if there's noone in room
        if(count($oldRoom->users) <= 1) {
            ChatMessage::query()->where('room_id', $oldRoom->id)->delete();
        }
    }
}
