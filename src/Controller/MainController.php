<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use OpenAI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Dotenv\Dotenv;

class MainController extends AbstractController
{
    #[Route('/')]
    public function generate(): Response
    {
        $yourApiKey = $this->getParameter('openai_api_key');
        //dump($yourApiKey);
        $client = OpenAI::client($yourApiKey);

        $response = $client->images()->create([
            'model' => 'dall-e-3',
            'prompt' => 'Generate whatever you want',
            'n' => 1,
            'size' => '1024x1024',
            'response_format' => 'url',
        ]);

       foreach ($response->data as $data) {
            $data->url; // 'https://oaidalleapiprodscus.blob.core.windows.net/private/...'
            $data->b64_json; // null
        }

        $responseArray = $response->toArray();

        dump($responseArray);

        return new Response(
            '<html><body><img src="'. $responseArray["data"][0]["url"].'" alt="Auto generated image" /></body></html>'
        );
    }
}