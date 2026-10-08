<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        return view('admin.berita', [
            'articles' => Article::query()->latest()->paginate(15),
        ]);
    }
}
