<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBulletinRequest;
use App\Http\Requests\UpdateBulletinRequest;
use App\Models\Bulletin;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class BulletinController extends Controller
{
    public function index(): View
    {
        return view('admin.buletin', [
            'bulletins' => Bulletin::query()->latest('tahun')->latest('id')->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.buletin-form', ['bulletin' => null]);
    }

    public function store(StoreBulletinRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['sampul'] = $request->file('sampul')->store('bulletins/covers', 'public');
        $data['file_pdf'] = $request->file('file_pdf')->store('bulletins/pdfs', 'public');

        $bulletin = Bulletin::query()->create($data);

        return redirect()
            ->route('admin.buletin.show', $bulletin)
            ->with('success', 'Buletin berhasil ditambahkan.');
    }

    public function show(Bulletin $buletin): View
    {
        return view('admin.buletin-detail', ['bulletin' => $buletin]);
    }

    public function edit(Bulletin $buletin): View
    {
        return view('admin.buletin-form', ['bulletin' => $buletin]);
    }

    public function update(UpdateBulletinRequest $request, Bulletin $buletin): RedirectResponse
    {
        $data = $request->validated();
        $previousFiles = [];

        foreach (['sampul', 'file_pdf'] as $field) {
            if ($request->hasFile($field)) {
                $previousFiles[] = $buletin->{$field};
                $data[$field] = $request->file($field)->store(
                    $field === 'sampul' ? 'bulletins/covers' : 'bulletins/pdfs',
                    'public',
                );
            } else {
                unset($data[$field]);
            }
        }

        $buletin->update($data);

        foreach ($previousFiles as $previousFile) {
            if ($previousFile !== null) {
                Storage::disk('public')->delete($previousFile);
            }
        }

        return redirect()
            ->route('admin.buletin.show', $buletin)
            ->with('success', 'Buletin berhasil diperbarui.');
    }

    public function destroy(Bulletin $buletin): RedirectResponse
    {
        $files = [$buletin->sampul, $buletin->file_pdf];

        $buletin->delete();

        foreach ($files as $file) {
            if ($file !== null) {
                Storage::disk('public')->delete($file);
            }
        }

        return redirect()
            ->route('admin.buletin.index')
            ->with('success', 'Buletin dan berkasnya berhasil dihapus.');
    }
}
