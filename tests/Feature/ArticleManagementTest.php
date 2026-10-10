<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_and_edit_screens_offer_the_rich_text_editor(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('admin.berita.create'))
            ->assertOk()
            ->assertSee('data-rich-editor', false)
            ->assertSee('data-rich-command="bold"', false)
            ->assertSee('data-rich-command="italic"', false)
            ->assertSee('data-rich-command="underline"', false)
            ->assertSee('data-rich-command="insertOrderedList"', false)
            ->assertSee('data-rich-command="insertUnorderedList"', false);

        $article = $this->createArticle();

        $this->get(route('admin.berita.edit', $article))
            ->assertOk()
            ->assertSee('value="Artikel Uji"', false);
    }

    public function test_authenticated_admin_can_create_and_view_sanitized_article(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('admin.berita.store'), [
            'judul' => 'Berita Pertama',
            'ringkasan' => 'Ringkasan berita.',
            'konten' => '<p>Konten <strong>tebal</strong><script>alert(1)</script><img src=x onerror=alert(1)></p>',
        ]);

        $article = Article::query()->firstOrFail();

        $response->assertRedirect(route('admin.berita.show', $article));
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'judul' => 'Berita Pertama',
            'slug' => 'berita-pertama',
            'konten' => '<p>Konten <strong>tebal</strong></p>',
            'is_archived' => false,
        ]);

        $this->get(route('admin.berita.show', $article))
            ->assertOk()
            ->assertSee('<strong>tebal</strong>', false)
            ->assertDontSee('alert(1)', false)
            ->assertDontSee('onerror', false);
    }

    public function test_authenticated_admin_can_update_article(): void
    {
        $this->actingAs(User::factory()->create());
        $article = $this->createArticle();

        $response = $this->put(route('admin.berita.update', $article), [
            'judul' => 'Judul Baru',
            'ringkasan' => 'Ringkasan baru.',
            'konten' => '<p>Konten baru</p><ul><li>Butir</li></ul>',
        ]);

        $response->assertRedirect(route('admin.berita.show', $article));
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'judul' => 'Judul Baru',
            'slug' => 'judul-baru',
            'ringkasan' => 'Ringkasan baru.',
            'konten' => '<p>Konten baru</p><ul><li>Butir</li></ul>',
        ]);
    }

    public function test_archiving_marks_article_and_shows_it_in_archived_filter(): void
    {
        $this->actingAs(User::factory()->create());
        $article = $this->createArticle();

        $response = $this->post(route('admin.berita.archive', $article));

        $response->assertRedirect(route('admin.berita.index', ['archived' => 1]));
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'is_archived' => true,
        ]);
        $this->get(route('admin.berita.index'))
            ->assertOk()
            ->assertDontSee($article->judul);
        $this->get(route('admin.berita.index', ['archived' => 1]))
            ->assertOk()
            ->assertSee($article->judul);
    }

    public function test_article_management_requires_authentication(): void
    {
        $article = $this->createArticle();

        $this->get(route('admin.berita.index'))->assertRedirect(route('login'));
        $this->get(route('admin.berita.show', $article))->assertRedirect(route('login'));
        $this->post(route('admin.berita.store'), [])->assertRedirect(route('login'));
    }

    public function test_invalid_article_data_is_not_saved(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('admin.berita.store'), [
            'judul' => '',
            'konten' => '',
        ]);

        $response->assertSessionHasErrors(['judul', 'konten']);
        $this->assertDatabaseCount('articles', 0);
    }

    private function createArticle(): Article
    {
        return Article::query()->create([
            'judul' => 'Artikel Uji',
            'slug' => 'artikel-uji',
            'konten' => '<p>Konten uji.</p>',
        ]);
    }
}
