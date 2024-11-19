<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableInterface;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact implements TimestampableInterface
{

    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $companyName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $principalSalutation = null;

    #[ORM\Column(length: 255)]
    private ?string $principalFirstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $principalLastName = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $displayName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailAddress = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $workPhoneNumber = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $mobilePhoneNumber = null;

    /**
     * @var Collection<int, ContactAddress>
     */
    #[ORM\OneToMany(targetEntity: ContactAddress::class, mappedBy: 'contact', orphanRemoval: true, cascade:["persist"])]
    private Collection $contactAddresses;

    /**
     * @var Collection<int, Invoice>
     */
    #[ORM\OneToMany(targetEntity: Invoice::class, mappedBy: 'customer')]
    private Collection $invoices;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $remarks = null;

    /**
     * @var Collection<int, Payment>
     */
    #[ORM\OneToMany(targetEntity: Payment::class, mappedBy: 'customer')]
    private Collection $payments;

    public function __construct()
    {
        $this->contactAddresses = new ArrayCollection();
        $this->uuid = Uuid::uuid4()->toString();
        $this->payments = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): static
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getPrincipalSalutation(): ?string
    {
        return $this->principalSalutation;
    }

    public function setPrincipalSalutation(?string $principalSalutation): static
    {
        $this->principalSalutation = $principalSalutation;

        return $this;
    }

    public function getPrincipalFirstName(): ?string
    {
        return $this->principalFirstName;
    }

    public function setPrincipalFirstName(string $principalFirstName): static
    {
        $this->principalFirstName = $principalFirstName;

        return $this;
    }

    public function getPrincipalLastName(): ?string
    {
        return $this->principalLastName;
    }

    public function setPrincipalLastName(?string $principalLastName): static
    {
        $this->principalLastName = $principalLastName;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(?string $emailAddress): static
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function getWorkPhoneNumber(): ?string
    {
        return $this->workPhoneNumber;
    }

    public function setWorkPhoneNumber(?string $workPhoneNumber): static
    {
        $this->workPhoneNumber = $workPhoneNumber;

        return $this;
    }

    public function getMobilePhoneNumber(): ?string
    {
        return $this->mobilePhoneNumber;
    }

    public function setMobilePhoneNumber(?string $mobilePhoneNumber): static
    {
        $this->mobilePhoneNumber = $mobilePhoneNumber;

        return $this;
    }

    /**
     * @return Collection<int, ContactAddress>
     */
    public function getContactAddresses(): Collection
    {
        return $this->contactAddresses;
    }

    public function addContactAddress(ContactAddress $contactAddress): static
    {
        if (!$this->contactAddresses->contains($contactAddress)) {
            $this->contactAddresses->add($contactAddress);
            $contactAddress->setContact($this);
        }

        return $this;
    }

    public function removeContactAddress(ContactAddress $contactAddress): static
    {
        if ($this->contactAddresses->removeElement($contactAddress)) {
            // set the owning side to null (unless already changed)
            if ($contactAddress->getContact() === $this) {
                $contactAddress->setContact(null);
            }
        }

        return $this;
    }

    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    public function setRemarks(?string $remarks): static
    {
        $this->remarks = $remarks;

        return $this;
    }

    public function getPrincipalContact(){
        return join(" ", [$this->getPrincipalSalutation(), $this->getPrincipalFirstName(), $this->getPrincipalLastName()]);
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

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
            $payment->setCustomer($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getCustomer() === $this) {
                $payment->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invoice>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): static
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setContact($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): static
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getContact() === $this) {
                $invoice->setContact(null);
            }
        }

        return $this;
    }

    public function getPendingAmount(){
        $invoices = $this->getInvoices();
        $amount = 0;
        foreach ($invoices as $invoice){
            if($invoice->getInvoiceStatus() !== 'Paid'){
                $amount += $invoice->getPendingAmount();
            }
        }
        return $amount;
    }

    public function getUnpaidInvoices(){
        $invoices = $this->getInvoices();
        foreach ($invoices as $invoice){
            if($invoice->getInvoiceStatus() === 'Paid'){
                $index = array_search($invoice, $invoices->toArray());
                unset($invoices[$index]);
            }
        }
        return $invoices->toArray();
    }

    public function getBillingAddress(){
        $addresses = $this->getContactAddresses();

        foreach ($addresses as $address){
            if($address->getType() === 'Billing'){
                return $address;
            }
        }

        return null;
    }
}
