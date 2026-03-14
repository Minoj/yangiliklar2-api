<?php

namespace App\Controller;

use App\Controller\Base\AbstractController;
use App\Entity\News;
use Doctrine\ORM\EntityManagerInterface;

class NewsCreateAction extends AbstractController
{
    public function __invoke(
        News $data,
        EntityManagerInterface $entityManager,
    ): News
    {
        $this->validate($data);

        $data->setCreatedBy($this->getUser());

        $entityManager->persist($data);
        $entityManager->flush();

        return $data;
    }
}
