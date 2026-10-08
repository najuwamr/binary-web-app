<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Bulletin;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'jumlahBerita' => Article::count(),
            'jumlahBuletin' => Bulletin::count(),
        ]);
    }
}
