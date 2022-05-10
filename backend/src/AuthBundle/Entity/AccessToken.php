<?php

namespace App\AuthBundle\Entity;

use App\AuthBundle\Repository\AccessTokenRepository;
use App\CommonBundle\Trait\TimeStampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccessTokenRepository::class)]
#[ORM\Table(indexes: ['name' => 'user_id_idx', 'columns' => ['user_id']])]
#[ORM\HasLifecycleCallbacks]
class AccessToken
{
    use TimeStampableTrait;

    public const DEFAULT_EXPIRES_AT_IN_MINUTES = 500;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $token;

    #[ORM\Column(type: 'integer')]
    private int $expiresAt = self::DEFAULT_EXPIRES_AT_IN_MINUTES;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpiresAt(): int
    {
        return $this->expiresAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function setExpiresAt(int $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
