<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableInterface;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice implements TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

    #[ORM\Column(nullable: true)]
    private ?bool $quote = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contact $customer = null;

    #[ORM\Column(length: 255)]
    private ?string $documentNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referenceNumber = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $documentDate = null;

    #[ORM\ManyToOne]
    private ?User $salesPerson = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $expiryDate = null;

    /**
     * @var Collection<int, InvoiceItem>
     */
    #[ORM\OneToMany(targetEntity: InvoiceItem::class, mappedBy: 'invoice', orphanRemoval: true, cascade:["persist"])]
    private Collection $invoiceItems;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $paymentMethod = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $paymentAccount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $quoteStatus = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $invoiceStatus = null;

    /**
     * @var Collection<int, Payment>
     */
    #[ORM\OneToMany(targetEntity: Payment::class, mappedBy: 'invoice')]
    private Collection $payments;

    #[ORM\Column]
    private ?float $pendingAmount = null;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->toString();
        $this->invoiceItems = new ArrayCollection();
        $this->payments = new ArrayCollection();
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

    public function isQuote(): ?bool
    {
        return $this->quote;
    }

    public function setQuote(?bool $quote): static
    {
        $this->quote = $quote;

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

    public function getDocumentNumber(): ?string
    {
        return $this->documentNumber;
    }

    public function setDocumentNumber(string $documentNumber): static
    {
        $this->documentNumber = $documentNumber;

        return $this;
    }

    public function getReferenceNumber(): ?string
    {
        return $this->referenceNumber;
    }

    public function setReferenceNumber(?string $referenceNumber): static
    {
        $this->referenceNumber = $referenceNumber;

        return $this;
    }

    public function getDocumentDate(): ?\DateTimeImmutable
    {
        return $this->documentDate;
    }

    public function setDocumentDate(\DateTimeImmutable $documentDate): static
    {
        $this->documentDate = $documentDate;

        return $this;
    }

    public function getSalesPerson(): ?User
    {
        return $this->salesPerson;
    }

    public function setSalesPerson(?User $salesPerson): static
    {
        $this->salesPerson = $salesPerson;

        return $this;
    }

    public function getExpiryDate(): ?\DateTimeImmutable
    {
        return $this->expiryDate;
    }

    public function setExpiryDate(?\DateTimeImmutable $expiryDate): static
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * @return Collection<int, InvoiceItem>
     */
    public function getInvoiceItems(): Collection
    {
        return $this->invoiceItems;
    }

    public function addInvoiceItem(InvoiceItem $invoiceItem): static
    {
        if (!$this->invoiceItems->contains($invoiceItem)) {
            $this->invoiceItems->add($invoiceItem);
            $invoiceItem->setInvoice($this);
        }

        return $this;
    }

    public function removeInvoiceItem(InvoiceItem $invoiceItem): static
    {
        if ($this->invoiceItems->removeElement($invoiceItem)) {
            // set the owning side to null (unless already changed)
            if ($invoiceItem->getInvoice() === $this) {
                $invoiceItem->setInvoice(null);
            }
        }

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

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getPaymentAccount(): ?string
    {
        return $this->paymentAccount;
    }

    public function setPaymentAccount(?string $paymentAccount): static
    {
        $this->paymentAccount = $paymentAccount;

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

    public function getQuoteStatus(): ?string
    {
        return $this->quoteStatus;
    }

    public function setQuoteStatus(?string $quoteStatus): static
    {
        $this->quoteStatus = $quoteStatus;

        return $this;
    }

    public function getInvoiceStatus(): ?string
    {
        return $this->invoiceStatus;
    }

    public function setInvoiceStatus(?string $invoiceStatus): static
    {
        $this->invoiceStatus = $invoiceStatus;

        return $this;
    }

    public function updateInvoiceStatus(): static
    {
        if($this->getPendingAmount() === floatval(0)){
            $status = 'Paid';
        } else if($this->getPendingAmount() === $this->getAmount()){
            $status = 'Unpaid';
        } else {
            $status = 'Partially Paid';
        }

        $this->invoiceStatus = $status;

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setInvoice($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getInvoice() === $this) {
                $payment->setInvoice(null);
            }
        }

        return $this;
    }

    public function getPendingAmount(): ?float
    {
        return $this->pendingAmount;
    }

    public function setPendingAmount(float $pendingAmount): static
    {
        $this->pendingAmount = $pendingAmount;

        return $this;
    }

    public function substractPaidAmount(float $paidAmount): static
    {
        $this->pendingAmount -= $paidAmount;

        return $this;
    }
}
