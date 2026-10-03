<?php

namespace Tests\Feature;

use App\Models\GassRoomFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GassRoomTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_requires_the_configured_access_code_before_showing_files(): void
    {
        config(['gass.access_code' => 'gass1234']);

        $this->get(route('gass.room'))
            ->assertRedirect(route('gass.access.form'));

        $this->get(route('gass.access.form'))
            ->assertOk()
            ->assertSee('Masukkan kode akses sebelum masuk ke ruang file.');

        $this->withSession(['gass_room_access' => true])
            ->get(route('gass.access.form'))
            ->assertOk()
            ->assertSee('Kode akses');

        $this->get(route('gass.room'))
            ->assertRedirect(route('gass.access.form'));

        $this->post(route('gass.access'), ['access_code' => 'wrong'])
            ->assertRedirect(route('gass.access.form'))
            ->assertSessionHasErrors('access_code');

        $this->post(route('gass.access'), ['access_code' => 'gass1234'])
            ->assertRedirect(route('gass.room'))
            ->assertSessionHas('gass_room_access', true);

        $this->get(route('gass.room'))->assertOk()->assertSee('Unggah File');
    }

    public function test_only_authorized_visitors_can_upload_and_download_files(): void
    {
        Storage::fake('local');
        $file = GassRoomFile::query()->create([
            'original_name' => 'private.bin',
            'path' => 'gass-room/private-file',
            'size_bytes' => 4,
            'mime_type' => 'application/octet-stream',
        ]);
        Storage::disk('local')->put($file->path, 'data');

        $this->get(route('gass.files.download', $file))->assertForbidden();
        $this->post(route('gass.files.upload'), [
            'files' => [UploadedFile::fake()->create('blocked.bin')],
        ])->assertForbidden();

        $this->withSession(['gass_room_access' => true])
            ->get(route('gass.room'))
            ->assertOk()
            ->assertSee('private.bin');

        $this->withSession(['gass_room_access' => true])
            ->get(route('gass.files.download', $file))
            ->assertOk()
            ->assertDownload('private.bin');
    }

    public function test_authorized_visitors_can_preview_safe_files_and_download_unsupported_types(): void
    {
        Storage::fake('local');
        $pdf = GassRoomFile::query()->create([
            'original_name' => 'guide.pdf',
            'path' => 'gass-room/guide-pdf',
            'size_bytes' => 4,
            'mime_type' => 'application/pdf',
        ]);
        $workbook = GassRoomFile::query()->create([
            'original_name' => 'sheet.xlsx',
            'path' => 'gass-room/sheet-xlsx',
            'size_bytes' => 4,
            'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
        Storage::disk('local')->put($pdf->path, '%PDF');
        Storage::disk('local')->put($workbook->path, 'xlsx');

        $this->get(route('gass.files.view', $pdf))->assertForbidden();

        $this->withSession(['gass_room_access' => true])
            ->get(route('gass.files.view', $pdf))
            ->assertOk()
            ->assertSee('data-preview-type="pdf"', false);

        $this->withSession(['gass_room_access' => true])
            ->get(route('gass.files.content', $pdf))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertHeader('X-Content-Type-Options', 'nosniff');

        $this->withSession(['gass_room_access' => true])
            ->get(route('gass.files.view', $workbook))
            ->assertOk()
            ->assertSee('data-preview-type="excel"', false)
            ->assertSee('gass-preview-', false);
    }

    public function test_image_video_audio_and_word_files_get_rich_previews(): void
    {
        $cases = [
            ['photo.png', 'image/png', 'image'],
            ['clip.mp4', 'video/mp4', 'video'],
            ['voice.mp3', 'audio/mpeg', 'audio'],
            ['guide.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'word'],
        ];

        foreach ($cases as [$name, $mimeType, $previewType]) {
            $file = GassRoomFile::query()->create([
                'original_name' => $name,
                'path' => 'gass-room/'.str_replace('.', '-', $name),
                'size_bytes' => 10,
                'mime_type' => $mimeType,
            ]);
            Storage::disk('local')->put($file->path, 'preview-data');

            $response = $this->withSession(['gass_room_access' => true])
                ->get(route('gass.files.view', $file));

            $response->assertOk()->assertSee('data-preview-type="'.$previewType.'"', false);
            if ($previewType === 'image') {
                $response->assertSee('<img src=', false);
            } elseif ($previewType === 'video') {
                $response->assertSee('<video src=', false);
            } elseif ($previewType === 'audio') {
                $response->assertSee('<audio src=', false);
            } else {
                $response->assertSee('gass-preview-', false);
            }
        }
    }

    public function test_only_authorized_visitors_can_delete_room_files(): void
    {
        Storage::fake('local');
        $file = GassRoomFile::query()->create([
            'original_name' => 'remove-me.txt',
            'path' => 'gass-room/remove-me',
            'size_bytes' => 4,
            'mime_type' => 'text/plain',
        ]);
        Storage::disk('local')->put($file->path, 'data');

        $this->delete(route('gass.files.destroy', $file))->assertForbidden();
        $this->assertDatabaseHas('gass_room_files', ['id' => $file->id]);

        $this->withSession(['gass_room_access' => true])
            ->delete(route('gass.files.destroy', $file))
            ->assertRedirect(route('gass.room'))
            ->assertSessionHas('success', 'File berhasil dihapus.');

        $this->assertDatabaseMissing('gass_room_files', ['id' => $file->id]);
        Storage::disk('local')->assertMissing($file->path);
    }

    public function test_room_search_filters_files_by_name(): void
    {
        GassRoomFile::query()->create([
            'original_name' => 'Panduan Klinik.pdf',
            'path' => 'gass-room/clinic-guide',
            'size_bytes' => 10,
            'mime_type' => 'application/pdf',
        ]);
        GassRoomFile::query()->create([
            'original_name' => 'Daftar Harga.xlsx',
            'path' => 'gass-room/prices',
            'size_bytes' => 20,
            'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);

        $this->withSession(['gass_room_access' => true])
            ->get(route('gass.room', ['search' => 'klinik']))
            ->assertOk()
            ->assertSee('Panduan Klinik.pdf')
            ->assertDontSee('Daftar Harga.xlsx');
    }

    public function test_upload_accepts_arbitrary_file_types_without_an_application_size_limit(): void
    {
        Storage::fake('local');
        $upload = UploadedFile::fake()->create('archive.unknown', 2048, 'application/octet-stream');

        $this->withSession(['gass_room_access' => true])
            ->post(route('gass.files.upload'), ['files' => [$upload]])
            ->assertRedirect(route('gass.room'))
            ->assertSessionHas('success');

        $storedFile = GassRoomFile::query()->firstOrFail();
        $this->assertSame('archive.unknown', $storedFile->original_name);
        $this->assertSame(2 * 1024 * 1024, $storedFile->size_bytes);
        Storage::disk('local')->assertExists($storedFile->path);
    }
}
