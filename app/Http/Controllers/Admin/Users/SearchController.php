<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\SearchUsersRequest;
use App\Http\Requests\Admin\Users\SortByUsersRequest;
use App\Models\User;

class SearchController extends Controller
{
    public function userSearch(SearchUsersRequest $request)
    {
        $searchTerm = $request->input('search');
        $searchTerm = trim($searchTerm); // Удаляем пробелы
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
                    ->orWhere('email', 'like', '%'.$searchTerm.'%');
            })
            ->limit(10)
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'users' => $users,
        ]);
    }

    public function sortBy(SortByUsersRequest $request)
    {
        $searchTerm = $request->input('sort_by');
        $offset = $request->input('sortByOffset', 0);
        $limit = $request->input('sortByLimit', 10);

        if ($searchTerm === 'all') {
            $users = User::select('id', 'name', 'surname', 'email', 'role', 'locale', 'created_at', 'email_verified_at')
                ->skip($offset)
                ->take($limit)
                ->orderByDesc('id')
                ->get();

            return response()->json([
                'users' => $users,
            ]);
        }

        $users = User::select('id', 'name', 'surname', 'email', 'role', 'locale', 'created_at', 'email_verified_at')
            ->where('role', $searchTerm)
            ->skip($offset)
            ->take($limit)
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'users' => $users,
        ]);
    }
}
