<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableInterface;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment implements TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    private ?Invoice $invoice = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $transactionDate = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(length: 255)]
    private ?string $paymentMethod = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContableAccount $contableAccount = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    private ?Contact $customer = null;

    #[ORM\OneToOne(mappedBy: 'associatedPayment', cascade: ['persist', 'remove'])]
    private ?JournalEntry $journalEntry = null;

    public function __construct()
    {
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

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): static
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getTransactionDate(): ?\DateTimeInterface
    {
        return $this->transactionDate;
    }

    public function setTransactionDate(\DateTimeInterface $transactionDate): static
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }


    public function getContableAccount(): ?ContableAccount
    {
        return $this->contableAccount;
    }

    public function setContableAccount(?ContableAccount $contableAccount): static
    {
        $this->contableAccount = $contableAccount;

        return $this;
    }

    public function getCustomer(): ?Contact
    {
        return $this->customer;
    }

    public function setCustomer(?Contact $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getJournalEntry(): ?JournalEntry
    {
        return $this->journalEntry;
    }

    public function setJournalEntry(?JournalEntry $journalEntry): static
    {
        // unset the owning side of the relation if necessary
        if ($journalEntry === null && $this->journalEntry !== null) {
            $this->journalEntry->setAssociatedPayment(null);
        }

        // set the owning side of the relation if necessary
        if ($journalEntry !== null && $journalEntry->getAssociatedPayment() !== $this) {
            $journalEntry->setAssociatedPayment($this);
        }

        $this->journalEntry = $journalEntry;

        return $this;
    }
}
