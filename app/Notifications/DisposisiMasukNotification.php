<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\SuratMasuk;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class DisposisiMasukNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly SuratMasuk $suratMasuk,
        public readonly string $pemberiNama,
        public readonly string $instruksi
    ) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $signedUrl = ! empty($this->suratMasuk->file_path)
            ? URL::temporarySignedRoute(
                'documents.stream',
                now()->addMinutes(15),
                ['document' => $this->suratMasuk->id]
            )
            : null;

        return [
            'surat_masuk_id' => $this->suratMasuk->id,
            'nomor_agenda' => $this->suratMasuk->nomor_agenda,
            'nomor_surat' => $this->suratMasuk->nomor_surat,
            'perihal' => $this->suratMasuk->perihal,
            'pemberi_nama' => $this->pemberiNama,
            'instruksi' => $this->instruksi,
            'stream_url' => $signedUrl,
            'created_at' => now()->toIso8601String(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
