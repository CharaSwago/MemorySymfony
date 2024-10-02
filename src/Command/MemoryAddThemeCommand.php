<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ThemeImage;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'memory:add-theme',
    description: 'Add cutsom card theme',
)]

class MemoryAddThemeCommand extends Command
{   
    private $httpClient;
    private $entityManager;

    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        parent::__construct();
    }




    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        //Ask to user the theme
        $theme = $io->ask('Which theme do you want import ?');
        $io->success('You have chosen the theme : ' .$theme);

        $response = $this->httpClient->request('GET', 'https://pixabay.com/api/', [
            //'query' makes it easier to add url parameters 
            'query' => [
                'key' => '46302554-700c54259a5421f3d9b5e6c5a', //API code retrieved from the pixabay website
                'q' => $theme,
                'image_type' => 'photo', //take only photos
            ],
        ]);
        
        //Put image's urls in a tab
        $content = $response->toArray();
  
        
        $images = $content['hits'];

        //browse images in the table and save them in the database
        foreach ($images as $image) {
            $themeImage = new ThemeImage();
            $themeImage->setTheme($theme);
            $themeImage->setImageUrl($image['webformatURL']);
            
            $this->entityManager->persist($themeImage);

        }

        //save all entities in the DB
        $this->entityManager->flush();

        $io->success('Les images du thème "' . $theme . '" ont été importées avec succès !'); 
        return Command::SUCCESS;
    }    

}
