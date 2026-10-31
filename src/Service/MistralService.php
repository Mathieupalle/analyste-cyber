<?php

namespace App\Service;

class MistralService
{
    const URL = 'https://api.mistral.ai/v1/chat/completions';
    const MODELE = 'mistral-small-latest';

    // demande au mentor IA de reformuler un indice
    // renvoie null si l'API ne répond pas (on utilisera alors l'indice de secours)
    public function demanderIndice(string $titreSalle, string $question, string $indiceSecours): ?string
    {
        $cle = getenv('MISTRAL_API_KEY');

        if (empty($cle)) {
            return null;
        }

        // la bonne réponse n'est jamais envoyée à l'API
        $donnees = [
            'model' => self::MODELE,
            'max_tokens' => 120,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Tu es un mentor en cybersécurité dans un escape game. Tu aides un analyste junior '
                        . 'avec un indice court (2 phrases maximum), en français, en le tutoyant. '
                        . 'Tu ne donnes jamais la réponse.',
                ],
                [
                    'role' => 'user',
                    'content' => 'Salle : ' . $titreSalle . "\n"
                        . 'Question : ' . $question . "\n"
                        . 'Idée de l\'indice : ' . $indiceSecours,
                ],
            ],
        ];

        $curl = curl_init(self::URL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $cle,
        ]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($donnees));
        curl_setopt($curl, CURLOPT_TIMEOUT, 8);

        $reponse = curl_exec($curl);
        $codeHttp = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($reponse === false || $codeHttp !== 200) {
            return null;
        }

        $json = json_decode($reponse, true);

        if (empty($json['choices'][0]['message']['content'])) {
            return null;
        }

        return trim($json['choices'][0]['message']['content']);
    }
}
