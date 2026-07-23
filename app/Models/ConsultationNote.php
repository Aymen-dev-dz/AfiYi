<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class ConsultationNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'consultation_id',
        'therapist_profile_id',
        'visibility',
        'content_encrypted',
        'encryption_iv',
        'tags',
        'is_session_summary',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_session_summary' => 'boolean',
        'content_encrypted' => 'encrypted',
    ];

    /**
     * Helper mutator to automatically generate IV if not present when creating
     */
    protected static function booted()
    {
        static::creating(function ($note) {
            if (empty($note->encryption_iv)) {
                $note->encryption_iv = bin2hex(random_bytes(32));
            }
        });
    }

    /**
     * On ne stocke jamais le contenu en clair dans les attributs.
     * Utiliser setContent() et getContent() explicitement.
     */

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function therapistProfile(): BelongsTo
    {
        return $this->belongsTo(TherapistProfile::class);
    }

    /**
     * Chiffre et stocke le contenu de la note.
     */
    public function setContent(string $plaintext): void
    {
        $iv = random_bytes(16);
        $key = base64_decode(config('app.note_encryption_key', base64_encode(random_bytes(32))));

        $encrypted = openssl_encrypt(
            $plaintext,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        $this->content_encrypted = base64_encode($encrypted);
        $this->encryption_iv     = base64_encode($iv);
        $this->save();
    }

    /**
     * Déchiffre et retourne le contenu de la note.
     */
    public function getContent(): ?string
    {
        if (!$this->content_encrypted) {
            return null;
        }

        $key = base64_decode(config('app.note_encryption_key', ''));
        $iv  = base64_decode($this->encryption_iv);

        return openssl_decrypt(
            base64_decode($this->content_encrypted),
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        ) ?: null;
    }

    /**
     * Retourne le contenu seulement si la note est partagée avec le patient.
     */
    public function getContentForPatient(): ?string
    {
        if ($this->visibility !== 'shared_with_patient') {
            return null;
        }
        return $this->getContent();
    }
}
