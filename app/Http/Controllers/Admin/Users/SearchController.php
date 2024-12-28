<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchUsers(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'warning' => 'You do not have permission to view this page.',
            ]);
        }

        $searchTerm = $request->input('search');
        $searchTerm = trim($searchTerm); // Удаляем пробелы

        if ($searchTerm === 'Administrator' || $searchTerm === 'Администратор' || $searchTerm === 'Адміністратор') {
            $searchTerm = 'admin';
        }

        if ($searchTerm === 'User' || $searchTerm === 'Пользователь' || $searchTerm === 'Користувач') {
            $searchTerm = 'user';
        } 

        $searchTerms = explode(' ', $searchTerm); // Разбиваем строку на массив по пробелам

        $users = User::select('id', 'name', 'surname', 'email', 'role', 'locale', 'created_at', 'email_verified_at')
            ->when(count($searchTerms) > 1, function ($query) use ($searchTerms) {
                // Если ввели два и более слов, выполняем поиск по имени и фамилии
                $query->where(function ($q) use ($searchTerms) {
                    $q->where('name', 'like', '%'.$searchTerms[0].'%')
                        ->where('surname', 'like', '%'.$searchTerms[1].'%');
                })->orWhere(function ($q) use ($searchTerms) {
                    $q->where('name', 'like', '%'.$searchTerms[1].'%')
                        ->where('surname', 'like', '%'.$searchTerms[0].'%');
                });
            }, function ($query) use ($searchTerm) {
                // Если одно слово, ищем во всех полях
                $query->where('name', 'like', '%'.$searchTerm.'%')
                    ->orWhere('surname', 'like', '%'.$searchTerm.'%')
                    ->orWhere('email', 'like', '%'.$searchTerm.'%')
                    ->orWhere('role', 'like', '%'.$searchTerm.'%');
            })
            ->orderByDesc('id')
            ->get();

        // $users = User::select('id', 'name', 'surname', 'email', 'role', 'locale', 'created_at', 'email_verified_at')
        //     ->where('name', 'like', '%'.$searchTerm.'%')
        //     ->orWhere('surname', 'like', '%'.$searchTerm.'%')
        //     ->orWhere('email', 'like', '%'.$searchTerm.'%')
        //     ->orWhere('role', 'like', '%'.$searchTerm.'%')
        //     ->orderByDesc('id')
        //     ->get();

        if (count($users) > 10) {
            return response()->json([
                'warning' => 'Too many users satisfy this request. Refine your search query.',
            ]);
        }

        return response()->json([
            'users' => $users,
        ]);
    }
}
