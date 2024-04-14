<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->string('note_key', 32)->nullable(); // Colonne pour stocker la clé de note
        });

        $notes = \App\Models\Note::all();
        foreach ($notes as $note) {
            $note->note_key = substr(md5(uniqid(rand(), true)), 0, 32); // Générer une nouvelle clé de note

            $content = File::get("storage/app" . $note->path);
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
            $encryptedData = openssl_encrypt($content, "aes-256-cbc", $note->note_key, 0, $iv);
            Storage::put($note->path,$iv . $encryptedData);
            $note->save();
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn('note_key'); // Supprimer la colonne lors du rollback
        });

        $notes = \App\Models\Note::all();
        foreach ($notes as $note){
            $path = storage_path('app/' . $note->path);
            $content = File::get($path);
            $ivSize = openssl_cipher_iv_length('aes-256-cbc');
            $iv = substr($content, 0, $ivSize);
            $encryptedData = substr($content, $ivSize);
            $decryptedData = openssl_decrypt($encryptedData, "aes-256-cbc", $note->note_key, 0, $iv);
        }

    }
};
