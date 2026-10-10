<?php

namespace Tests\Feature;

use App\Models\Bulletin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BulletinManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_bulletin_list_shows_cover_description_and_year(): void
    {
        $this->actingAs(User::factory()->create());
        $bulletin = $this->createBulletin();

        $this->get(route('admin.buletin.index'))
            ->assertOk()
            ->assertSee($bulletin->judul)
            ->assertSee($bulletin->deskripsi)
            ->assertSee((string) $bulletin->tahun)
            ->assertSee(Storage::disk('public')->url($bulletin->sampul));
    }

    public function test_create_and_edit_screens_show_the_bulletin_fields(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('admin.buletin.create'))
            ->assertOk()
            ->assertSee('name="deskripsi"', false)
            ->assertSee('name="sampul"', false)
            ->assertSee('name="file_pdf"', false);

        $bulletin = $this->createBulletin();

        $this->get(route('admin.buletin.edit', $bulletin))
            ->assertOk()
            ->assertSee('value="Buletin Uji"', false)
            ->assertSee('Deskripsi buletin uji.');
    }

    public function test_admin_can_create_and_open_a_bulletin_flipbook(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('admin.buletin.store'), [
            'judul' => 'Buletin Pertama',
            'deskripsi' => 'Deskripsi buletin pertama.',
            'edisi' => '01',
            'tahun' => 2026,
            'sampul' => UploadedFile::fake()->image('sampul.jpg'),
            'file_pdf' => UploadedFile::fake()->createWithContent('edisi.pdf', "%PDF-1.4\nIsi PDF"),
        ]);

        $bulletin = Bulletin::query()->firstOrFail();

        $response->assertRedirect(route('admin.buletin.show', $bulletin));
        $this->assertDatabaseHas('bulletins', [
            'id' => $bulletin->id,
            'judul' => 'Buletin Pertama',
            'deskripsi' => 'Deskripsi buletin pertama.',
            'edisi' => '01',
            'tahun' => 2026,
        ]);
        Storage::disk('public')->assertExists($bulletin->sampul);
        Storage::disk('public')->assertExists($bulletin->file_pdf);

        $this->get(route('admin.buletin.show', $bulletin))
            ->assertOk()
            ->assertSee('data-pdf-flipbook', false)
            ->assertSee('data-flip-next', false)
            ->assertSee($bulletin->judul);
    }

    public function test_admin_can_update_bulletin_and_replace_uploaded_files(): void
    {
        $this->actingAs(User::factory()->create());
        $bulletin = $this->createBulletin();
        $oldCover = $bulletin->sampul;
        $oldPdf = $bulletin->file_pdf;

        $response = $this->put(route('admin.buletin.update', $bulletin), [
            'judul' => 'Buletin Diperbarui',
            'deskripsi' => 'Deskripsi diperbarui.',
            'edisi' => '02',
            'tahun' => 2027,
            'sampul' => UploadedFile::fake()->image('sampul-baru.png'),
            'file_pdf' => UploadedFile::fake()->createWithContent('edisi-baru.pdf', "%PDF-1.4\nIsi PDF baru"),
        ]);

        $response->assertRedirect(route('admin.buletin.show', $bulletin));
        $bulletin->refresh();

        $this->assertDatabaseHas('bulletins', [
            'id' => $bulletin->id,
            'judul' => 'Buletin Diperbarui',
            'deskripsi' => 'Deskripsi diperbarui.',
            'edisi' => '02',
            'tahun' => 2027,
        ]);
        Storage::disk('public')->assertMissing($oldCover);
        Storage::disk('public')->assertMissing($oldPdf);
        Storage::disk('public')->assertExists($bulletin->sampul);
        Storage::disk('public')->assertExists($bulletin->file_pdf);
    }

    public function test_bulletin_update_keeps_files_when_no_replacements_are_uploaded(): void
    {
        $this->actingAs(User::factory()->create());
        $bulletin = $this->createBulletin();
        $cover = $bulletin->sampul;
        $pdf = $bulletin->file_pdf;

        $this->put(route('admin.buletin.update', $bulletin), [
            'judul' => 'Judul Diperbarui',
            'deskripsi' => 'Deskripsi diperbarui.',
            'edisi' => '02',
            'tahun' => 2027,
        ])->assertRedirect(route('admin.buletin.show', $bulletin));

        $bulletin->refresh();

        $this->assertSame($cover, $bulletin->sampul);
        $this->assertSame($pdf, $bulletin->file_pdf);
        Storage::disk('public')->assertExists($cover);
        Storage::disk('public')->assertExists($pdf);
    }

    public function test_admin_can_hard_delete_bulletin_and_its_files(): void
    {
        $this->actingAs(User::factory()->create());
        $bulletin = $this->createBulletin();
        $cover = $bulletin->sampul;
        $pdf = $bulletin->file_pdf;

        $response = $this->delete(route('admin.buletin.destroy', $bulletin));

        $response->assertRedirect(route('admin.buletin.index'));
        $this->assertDatabaseMissing('bulletins', ['id' => $bulletin->id]);
        Storage::disk('public')->assertMissing($cover);
        Storage::disk('public')->assertMissing($pdf);
    }

    public function test_bulletin_management_requires_authentication(): void
    {
        $bulletin = $this->createBulletin();

        $this->get(route('admin.buletin.index'))->assertRedirect(route('login'));
        $this->get(route('admin.buletin.show', $bulletin))->assertRedirect(route('login'));
        $this->post(route('admin.buletin.store'), [])->assertRedirect(route('login'));
    }

    public function test_invalid_bulletin_files_are_rejected(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('admin.buletin.store'), [
            'judul' => 'Buletin Tidak Valid',
            'deskripsi' => 'Deskripsi.',
            'edisi' => '01',
            'tahun' => 2026,
            'sampul' => UploadedFile::fake()->image('sampul.png'),
            'file_pdf' => UploadedFile::fake()->create('dokumen.txt', 10, 'text/plain'),
        ]);

        $response->assertSessionHasErrors('file_pdf');
        $this->assertDatabaseCount('bulletins', 0);
    }

    private function createBulletin(): Bulletin
    {
        $cover = UploadedFile::fake()->image('sampul.jpg')->store('bulletins/covers', 'public');
        $pdf = UploadedFile::fake()->createWithContent('edisi.pdf', "%PDF-1.4\nIsi PDF");

        return Bulletin::query()->create([
            'judul' => 'Buletin Uji',
            'deskripsi' => 'Deskripsi buletin uji.',
            'edisi' => '01',
            'tahun' => 2026,
            'sampul' => $cover,
            'file_pdf' => $pdf->store('bulletins/pdfs', 'public'),
        ]);
    }
}
