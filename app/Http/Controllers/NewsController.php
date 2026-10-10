<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Support\RichTextSanitizer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $isArchived = $request->boolean('archived');
        $articles = Article::query()
            ->where('is_archived', $isArchived)
            ->latest()
            ->paginate(15);

        return view('admin.berita', compact('articles', 'isArchived'));
    }

    public function create(RichTextSanitizer $sanitizer): View
    {
        return view('admin.berita-form', [
            'article' => null,
            'kontenAman' => $sanitizer->sanitize((string) old('konten', '')),
        ]);
    }

    public function store(StoreArticleRequest $request, RichTextSanitizer $sanitizer): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['judul']);
        $data['konten'] = $sanitizer->sanitize($data['konten']);
        $data['gambar'] = $request->file('gambar')?->store('articles', 'public');

        $article = Article::create($data);

        return redirect()
            ->route('admin.berita.show', $article)
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    public function show(Article $article, RichTextSanitizer $sanitizer): View
    {
        return view('admin.berita-detail', [
            'article' => $article,
            'kontenAman' => $sanitizer->sanitize($article->konten),
        ]);
    }

    public function edit(Article $article, RichTextSanitizer $sanitizer): View
    {
        return view('admin.berita-form', [
            'article' => $article,
            'kontenAman' => $sanitizer->sanitize((string) old('konten', $article->konten)),
        ]);
    }

    public function update(
        UpdateArticleRequest $request,
        Article $article,
        RichTextSanitizer $sanitizer,
    ): RedirectResponse {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['judul'], $article);
        $data['konten'] = $sanitizer->sanitize($data['konten']);
        $previousImage = $article->gambar;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('articles', 'public');
        } else {
            unset($data['gambar']);
        }

        $article->update($data);

        if ($request->hasFile('gambar') && $previousImage !== null) {
            Storage::disk('public')->delete($previousImage);
        }

        return redirect()
            ->route('admin.berita.show', $article)
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function archive(Article $article): RedirectResponse
    {
        $article->update(['is_archived' => true]);

        return redirect()
            ->route('admin.berita.index', ['archived' => 1])
            ->with('success', 'Berita berhasil diarsipkan.');
    }

    private function uniqueSlug(string $title, ?Article $article = null): string
    {
        $baseSlug = Str::slug($title) ?: 'berita';
        $slug = $baseSlug;
        $suffix = 2;

        while (Article::query()
            ->where('slug', $slug)
            ->when($article, fn ($query) => $query->where('id', '!=', $article->getKey()))
            ->exists()) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
