<?php

namespace App\Controller;

use App\Entity\Snippet;
use App\Form\SnippetAIType;
use App\Service\SnippetAI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Georgehadjisavva\ElevenLabsClient\ElevenLabsClient;
use Georgehadjisavva\ElevenLabsClient\TextToSpeech;

class SnippetController extends AbstractController
{
    #[Route('/snippet/{id}', name: 'show_code')]
    public function index(
        Snippet $snippet, 
        Request $request,
        // TextToSpeech $tts,
    ): Response
    {   
        $form = $this->createForm(SnippetAIType::class);
        // On récupère les données du formulaire
        $form->handleRequest($request);
        // On vérifie que le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()) {
            // On le code pour l'envoyer à l'IA
            $data = $form->getData('code');
            // On envoie les données à l'IA et elle renvoie une explication
            $explication = SnippetAI::explain($data);
            //Si GPT 3.5 a répondu ALORS ici on fait Text to Speech avec ElevenLabs
            if($explication) {
            //     // On va initialiser l'url de l'API
            //     $api = "https://api.elevenlabs.io/v1/text-to-speech/CYw3kZ02Hs0563khs1Fj/stream?optimize_streaming_latency=0&output_format=mp3_44100_128";
            //     // On va initialiser la clé API
            //     $key = $this->getParameter('ELEVEN_API_KEY');
            //     // On initialise les headers
            //     $headers = [
            //     'accept: */*',
            //     'xi-api-key: ' . $key,
            //     'Content-Type: application/json'
            //     ];
            //     // On initialise cURL
            //     $curl = curl_init();
            //     // On configure cURL
            //     curl_setopt($curl, CURLOPT_URL, $api);
            //     // Option pour la récupération du résultat
            //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            //     // On désactive la vérification du certificat SSL
            //     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            //     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            //     // Option pour la méthode POST
            //     curl_setopt($curl, CURLOPT_POST, true);
            //     // Option pour les headers
            //     curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            //     // Option pour le body
            //     curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
            //     'text' => $explication,
            //     'model_id' => 'eleven_multilingual_v1',
            //     'voice_settings' => [
            //     'stability' => 0,
            //     'similarity_boost' => 0,
            //     'style' => 0,
            //     'use_speaker_boost' => true
            //     ]
            //     ]));
            // // On exécute cURL
            // curl_exec($curl);
            // // Catch des erreurs
            // if (curl_errno($curl)) {
            //     return new Response(curl_error($curl));
            // }
            // // On ferme cURL
            // curl_close($curl);

            // dd($curl);

                // $audio = '';

                // On affiche le résultat dans le template twig
                return $this->render('snippet/snippet.html.twig', [
                    'snippet' => $snippet,
                    'SnippetAI' => $form,
                    'Explication' => $explication, // Cette variable contient la réponse de l'IA
                    'audio' => $audio // Contient le lien vers le fichier audio
                ]);
            }   
        }

        return $this->render('snippet/snippet.html.twig', [
            'snippet' => $snippet,
            'SnippetAI' => $form,
            'Explication' => '',
            'audio' => '',
        ]);
    }
}
