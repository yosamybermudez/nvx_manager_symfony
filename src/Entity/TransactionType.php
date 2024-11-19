<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableInterface;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\TransactionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: TransactionTypeRepository::class)]
class TransactionType implements TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, TransactionCategory>
     */
    #[ORM\OneToMany(targetEntity: TransactionCategory::class, mappedBy: 'transactionType')]
    private Collection $transactionCategories;

    public function __construct()
    {
        $this->transactionCategories = new ArrayCollection();
        $this->uuid = Uuid::uuid4()->toString();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, TransactionCategory>
     */
    public function getTransactionCategories(): Collection
    {
        return $this->transactionCategories;
    }

    public function addTransactionCategory(TransactionCategory $transactionCategory): static
    {
        if (!$this->transactionCategories->contains($transactionCategory)) {
            $this->transactionCategories->add($transactionCategory);
            $transactionCategory->setTransactionType($this);
        }

        return $this;
    }

    public function removeTransactionCategory(TransactionCategory $transactionCategory): static
    {
        if ($this->transactionCategories->removeElement($transactionCategory)) {
            // set the owning side to null (unless already changed)
            if ($transactionCategory->getTransactionType() === $this) {
                $transactionCategory->setTransactionType(null);
            }
        }

        return $this;
    }
}
