<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bulletin;
use Illuminate\Contracts\View\View;

class BulletinController extends Controller
{
    public function index(): View
    {
        return view('admin.buletin', [
            'bulletins' => Bulletin::query()->latest()->paginate(15),
        ]);
    }
}
