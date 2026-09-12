<?php

declare(strict_types=1);

namespace App\Livewire\Pengaturan;

use App\Constants\AppConstants;
use App\Models\PengaturanDokumen;
use Illuminate\Contracts\View\View;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Dokumen extends Component
{
    use WithFileUploads;

    public string $nama_institusi = '';

    public string $nama_fakultas = '';

    public string $alamat_lengkap = '';

    public string $telepon = '';

    public string $email = '';

    public string $website = '';

    public string $kota_penerbitan = '';

    public string $catatan_footer = '';

    public mixed $logo = null;

    public ?string $existing_logo_path = null;

    public function mount(): void
    {
        $setting = PengaturanDokumen::getAktif();

        $this->nama_institusi = $setting->nama_institusi ?? AppConstants::INSTITUSI_DEFAULT;
        $this->nama_fakultas = $setting->nama_fakultas ?? AppConstants::FAKULTAS_DEFAULT;
        $this->alamat_lengkap = $setting->alamat_lengkap ?? '';
        $this->telepon = $setting->telepon ?? '';
        $this->email = $setting->email ?? '';
        $this->website = $setting->website ?? '';
        $this->kota_penerbitan = $setting->kota_penerbitan ?? 'Semarang';
        $this->catatan_footer = $setting->catatan_footer ?? '';
        $this->existing_logo_path = $setting->logo_path;
    }

    public function simpan(): void
    {
        $rules = [
            'nama_institusi' => ['required', 'string', 'max:255'],
            'nama_fakultas' => ['nullable', 'string', 'max:255'],
            'alamat_lengkap' => ['required', 'string', 'max:500'],
            'telepon' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'website' => ['nullable', 'string', 'max:100'],
            'kota_penerbitan' => ['required', 'string', 'max:100'],
            'catatan_footer' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg', 'max:2048'], // Max 2MB
        ];

        $messages = [
            'nama_institusi.required' => 'Nama perguruan tinggi / institusi wajib diisi.',
            'alamat_lengkap.required' => 'Alamat lengkap lokasi kampus wajib diisi.',
            'email.email' => 'Format email resmi tidak valid.',
            'kota_penerbitan.required' => 'Kota penerbitan dokumen resmi wajib diisi.',
            'logo.image' => 'Berkas logo harus berupa gambar (PNG, JPG, SVG).',
            'logo.max' => 'Ukuran berkas logo maksimal 2 Megabyte.',
        ];

        $validated = $this->validate($rules, $messages);

        $setting = PengaturanDokumen::getAktif();

        $logoPath = $setting->logo_path;
        if ($this->logo instanceof UploadedFile) {
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }
            $logoPath = $this->logo->store('setting-dokumen', 'public');
        }

        $setting->update([
            'nama_institusi' => trim($validated['nama_institusi']),
            'nama_fakultas' => $validated['nama_fakultas'] ? trim($validated['nama_fakultas']) : null,
            'alamat_lengkap' => trim($validated['alamat_lengkap']),
            'telepon' => $validated['telepon'] ? trim($validated['telepon']) : null,
            'email' => $validated['email'] ? trim($validated['email']) : null,
            'website' => $validated['website'] ? trim($validated['website']) : null,
            'kota_penerbitan' => trim($validated['kota_penerbitan']),
            'catatan_footer' => $validated['catatan_footer'] ? trim($validated['catatan_footer']) : null,
            'logo_path' => $logoPath,
            'updated_by' => auth()->id(),
        ]);

        $this->existing_logo_path = $logoPath;
        $this->logo = null;

        $pesan = 'Pengaturan Kop Surat dan templat dokumen berhasil diperbarui.';
        $this->dispatch('show-toast', type: 'success', message: $pesan);
        session()->flash('success', $pesan);
    }

    public function render(): View
    {
        return view('livewire.pengaturan.dokumen');
    }
}
