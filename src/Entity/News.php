<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\NewsCreateAction;
use App\Repository\NewsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['news:item', 'user:read']],
            security: "is_granted('PUBLIC_ACCESS')"
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['news:list', 'user:read']],
            security: "is_granted('PUBLIC_ACCESS')"
        ),
        new Post(
            controller: NewsCreateAction::class,
            denormalizationContext: ['groups' => ['news:write']],
            security: "is_granted('ROLE_USER')",
            name: 'createNews'
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') || object.getCreatedBy() == user",
            name: 'deleteNews'
        )
    ],
    normalizationContext: ['groups' => ['news:list', 'news:item', 'user:read']],
    denormalizationContext: ['groups' => ['news:write']]
)]
class News
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['news:list', 'news:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['news:list', 'news:item', 'news:write'])]
    private ?string $theme = null;

    #[ORM\Column(length: 255)]
    #[Groups(['news:list', 'news:write'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['news:item', 'news:write'])]
    private ?string $text = null;

    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'news')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['news:list', 'news:item'])]
    private ?User $createdBy = null;

    #[ORM\Column]
    #[Groups(['news:list', 'news:item'])]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
