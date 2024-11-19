<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableInterface;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\JournalEntryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: JournalEntryRepository::class)]
class JournalEntry implements TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $entryDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

//    #[ORM\Column]
//    private ?float $amount = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $currency = null;

    #[ORM\Column(nullable: true)]
    private ?float $debit = null;

    #[ORM\Column(nullable: true)]
    private ?float $credit = null;

    #[ORM\Column]
    private ?float $balance = null;

    #[ORM\Column(length: 255)]
    private ?string $paymentMethod = null;

    #[ORM\ManyToOne]
    private ?Contact $contact = null;

    #[ORM\ManyToOne]
    private ?Invoice $invoice = null;

    #[ORM\ManyToOne]
    private ?User $employee = null;

    #[ORM\ManyToOne]
    private ?Project $project = null;

    #[ORM\ManyToOne(inversedBy: 'journalEntries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContableAccount $contableAccount = null;

    #[ORM\ManyToOne(inversedBy: 'journalEntries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TransactionCategory $transactionCategory = null;

    #[ORM\OneToOne(inversedBy: 'journalEntry', cascade: ['persist', 'remove'])]
    private ?Payment $associatedPayment = null;

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

    public function getEntryDate(): ?\DateTimeInterface
    {
        return $this->entryDate;
    }

    public function setEntryDate(\DateTimeInterface $entryDate): static
    {
        $this->entryDate = $entryDate;

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

//    public function getAmount(): ?float
//    {
//        return $this->amount;
//    }
//
//    public function setAmount(float $amount): static
//    {
//        $this->amount = $amount;
//
//        return $this;
//    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getDebit(): ?float
    {
        return $this->debit;
    }

    public function setDebit(?float $debit): static
    {
        $this->debit = $debit;

        return $this;
    }

    public function getCredit(): ?float
    {
        return $this->credit;
    }

    public function setCredit(?float $credit): static
    {
        $this->credit = $credit;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): static
    {
        $this->balance = $balance;

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

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): static
    {
        $this->contact = $contact;

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

    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    public function setEmployee(?User $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

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

    public function getTransactionCategory(): ?TransactionCategory
    {
        return $this->transactionCategory;
    }

    public function setTransactionCategory(?TransactionCategory $transactionCategory): static
    {
        $this->transactionCategory = $transactionCategory;

        return $this;
    }

    public function getAssociatedPayment(): ?Payment
    {
        return $this->associatedPayment;
    }

    public function setAssociatedPayment(?Payment $associatedPayment): static
    {
        $this->associatedPayment = $associatedPayment;

        return $this;
    }
}
