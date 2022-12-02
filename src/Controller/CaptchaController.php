<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Orhanerday\OpenAi\OpenAi;

class CaptchaController extends AbstractController
{

    

    


    #[Route('/captcha', name: 'app_captcha')]
    public function index(): Response
    {

        function get_string_between($string, $start, $end){
            $string = ' ' . $string;
            $ini = strpos($string, $start);
            if ($ini == 0) return '';
            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;
            return substr($string, $ini, $len);
        }

        $open_ai_key = "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";
        $open_ai = new OpenAi($open_ai_key);

        $complete = $open_ai->complete([
            'engine' => 'text-davinci-003',
            'prompt' => 'The following is a conversation with an AI assistant. The assistant is helpful, creative, clever, and very friendly.\n\nHuman: Hello, who are you?\nAI: I am an AI created by OpenAI. How can I help you today?\nHuman: Write me a stor in 4 steps happening at night.',
            'temperature' => 0.9,
            'max_tokens' => 1000,
            'frequency_penalty' => 0,
            'presence_penalty' => 0.6,
        ]);

        $temp = $complete;

        $text = get_string_between($complete, '"text"', ',"index"');
        
      
        $stepsString = [
        "step1" => get_string_between($text, 'Step 1: ', 'Step 2:'),
        "step2" => get_string_between($text, 'Step 2: ', 'Step 3:'),
        "step3" => get_string_between($text, 'Step 3: ', 'Step 4:'),
        "step4" => get_string_between($text, 'Step 4: ', '"')
        ];

        $stepUrlImages =array();
        foreach ($stepsString as $cle => $string ){
            $completeImage = $open_ai->image([
                "prompt" => $string,
                "n" => 1,
                "size" => "256x256",
                "response_format" => "url",
             ]);
             array_push($stepUrlImages, get_string_between($completeImage, '"url": "', '"'));
        }
        // $temp = $complete;

        //var_dump($stepUrlImages);
        return $this->render('captcha/index.html.twig', [
            'temp' => $temp,
            'images' => $stepUrlImages,
            'steps' => $stepsString,
            'controller_name' => 'CaptchaController',
        ]);
    }
}
