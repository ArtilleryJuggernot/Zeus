<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\CommonMarkConverter;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Support\Facades\File;

class PDFController extends Controller
{
    public function DonwloadNote(Request $request)
    {

        $output = new ConsoleOutput();
        $output->writeln("Oui je suis dans download");

        $note_id = $request->get("note_id");
        $user_id = $request->get("user_id");

        $note = Note::find($note_id);
        if (!$note) {
            return redirect()->route("home")->with("failure", "Vous ne pouvez pas télécharger cette ressource en PDF");
        }

        // Déchiffrer la note
        $content = File::get(storage_path('app/' . $note->path));
        $ivSize = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($content, 0, $ivSize);
        $encryptedData = substr($content, $ivSize);
        $markdown = openssl_decrypt($encryptedData, "aes-256-cbc", $note->note_key, 0, $iv);

        $output->writeln($markdown);

        // Convertir le Markdown en HTML
        $converter = new CommonMarkConverter();
        $html = $converter->convertToHtml($markdown);

        // Générer le PDF à partir du HTML
        $pdf = Pdf::loadHTML($html);

        // Chemin du fichier temporaire pour le PDF
        $PDFFilePath = storage_path('app/temp/' . $note->name . ".pdf");

        // Assurez-vous que le dossier temporaire existe
        if (!Storage::exists('temp')) {
            Storage::makeDirectory('temp');
        }

        // Sauvegarder le PDF
        File::put($PDFFilePath, $pdf->output());


        $output->writeln($pdf->output());
        $output->writeln($PDFFilePath);

        // Stream le fichier PDF au client
        return response()->download($PDFFilePath, $note->name . '.pdf')->deleteFileAfterSend(true);
    }
}
