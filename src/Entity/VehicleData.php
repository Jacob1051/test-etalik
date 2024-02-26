<?php

namespace App\Entity;

use App\Repository\VehicleDataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleDataRepository::class)]
class VehicleData
{
    CONST TYPEVNVO_CHOICE = [
        'VN' => 'VN',
        'VO' => 'VO'
    ];

    CONST TYPEPROSPECT_CHOICE = [
        'PARTICULIER' => 'PARTICULIER',
        'SOCIETE' => 'SOCIETE'
    ];

    CONST LIBELLECIVILITE_CHOICE = [
        "Mr" => "Mr",
        "Mme" => "Mme",
        "Ste" => "Ste"
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $compteAffaire = null;

    #[ORM\Column(length: 255)]
    private ?string $compteEvenementVeh = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $compteDernierEvenementVeh = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroDeFiche = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelleCivilite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $proprietaireActuelDuVehicule = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroEtNomDeLaVoie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $complementAdresse1 = null;

    #[ORM\Column(length: 255)]
    private ?string $codePostal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephoneDomicile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephonePortable = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephoneJob = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDeMiseEnCirculation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAchatDateDeLivraison = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDernierEvenementVeh = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleMarqueMrq = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelleModeleMod = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $version = null;

    #[ORM\Column(length: 255)]
    private ?string $vin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $immatriculation = null;

    #[ORM\Column(length: 255)]
    private ?string $typeDeProspect = null;

    #[ORM\Column]
    private ?float $kilometrage = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelleEnergieEnerg = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $vendeurVN = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $vendeurVO = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaireDeFacturationVeh = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeVNVO = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroDeDossierVNVO = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $intermediaireDeVenteVN = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateEvenementVeh = null;

    #[ORM\Column(length: 255)]
    private ?string $origineEvenementVeh = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompteAffaire(): ?string
    {
        return $this->compteAffaire;
    }

    public function setCompteAffaire(string $compteAffaire): static
    {
        $this->compteAffaire = $compteAffaire;

        return $this;
    }

    public function getCompteEvenementVeh(): ?string
    {
        return $this->compteEvenementVeh;
    }

    public function setCompteEvenementVeh(string $compteEvenementVeh): static
    {
        $this->compteEvenementVeh = $compteEvenementVeh;

        return $this;
    }

    public function getCompteDernierEvenementVeh(): ?string
    {
        return $this->compteDernierEvenementVeh;
    }

    public function setCompteDernierEvenementVeh(?string $compteDernierEvenementVeh): static
    {
        $this->compteDernierEvenementVeh = $compteDernierEvenementVeh;

        return $this;
    }

    public function getNumeroDeFiche(): ?string
    {
        return $this->numeroDeFiche;
    }

    public function setNumeroDeFiche(string $numeroDeFiche): static
    {
        $this->numeroDeFiche = $numeroDeFiche;

        return $this;
    }

    public function getLibelleCivilite(): ?string
    {
        return $this->libelleCivilite;
    }

    public function setLibelleCivilite(?string $libelleCivilite): static
    {
        $this->libelleCivilite = $libelleCivilite;

        return $this;
    }

    public function getProprietaireActuelDuVehicule(): ?string
    {
        return $this->proprietaireActuelDuVehicule;
    }

    public function setProprietaireActuelDuVehicule(?string $proprietaireActuelDuVehicule): static
    {
        $this->proprietaireActuelDuVehicule = $proprietaireActuelDuVehicule;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNumeroEtNomDeLaVoie(): ?string
    {
        return $this->numeroEtNomDeLaVoie;
    }

    public function setNumeroEtNomDeLaVoie(string $numeroEtNomDeLaVoie): static
    {
        $this->numeroEtNomDeLaVoie = $numeroEtNomDeLaVoie;

        return $this;
    }

    public function getComplementAdresse1(): ?string
    {
        return $this->complementAdresse1;
    }

    public function setComplementAdresse1(?string $complementAdresse1): static
    {
        $this->complementAdresse1 = $complementAdresse1;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTelephoneDomicile(): ?string
    {
        return $this->telephoneDomicile;
    }

    public function setTelephoneDomicile(?string $telephoneDomicile): static
    {
        $this->telephoneDomicile = $telephoneDomicile;

        return $this;
    }

    public function getTelephonePortable(): ?string
    {
        return $this->telephonePortable;
    }

    public function setTelephonePortable(?string $telephonePortable): static
    {
        $this->telephonePortable = $telephonePortable;

        return $this;
    }

    public function getTelephoneJob(): ?string
    {
        return $this->telephoneJob;
    }

    public function setTelephoneJob(?string $telephoneJob): static
    {
        $this->telephoneJob = $telephoneJob;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getDateDeMiseEnCirculation(): ?\DateTimeInterface
    {
        return $this->dateDeMiseEnCirculation;
    }

    public function setDateDeMiseEnCirculation(?\DateTimeInterface $dateDeMiseEnCirculation): static
    {
        $this->dateDeMiseEnCirculation = $dateDeMiseEnCirculation;

        return $this;
    }

    public function getDateAchatDateDeLivraison(): ?\DateTimeInterface
    {
        return $this->dateAchatDateDeLivraison;
    }

    public function setDateAchatDateDeLivraison(?\DateTimeInterface $dateAchatDateDeLivraison): static
    {
        $this->dateAchatDateDeLivraison = $dateAchatDateDeLivraison;

        return $this;
    }

    public function getDateDernierEvenementVeh(): ?\DateTimeInterface
    {
        return $this->dateDernierEvenementVeh;
    }

    public function setDateDernierEvenementVeh(?\DateTimeInterface $dateDernierEvenementVeh): static
    {
        $this->dateDernierEvenementVeh = $dateDernierEvenementVeh;

        return $this;
    }

    public function getLibelleMarqueMrq(): ?string
    {
        return $this->libelleMarqueMrq;
    }

    public function setLibelleMarqueMrq(string $libelleMarqueMrq): static
    {
        $this->libelleMarqueMrq = $libelleMarqueMrq;

        return $this;
    }

    public function getLibelleModeleMod(): ?string
    {
        return $this->libelleModeleMod;
    }

    public function setLibelleModeleMod(?string $libelleModeleMod): static
    {
        $this->libelleModeleMod = $libelleModeleMod;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getVin(): ?string
    {
        return $this->vin;
    }

    public function setVin(string $vin): static
    {
        $this->vin = $vin;

        return $this;
    }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(?string $immatriculation): static
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    public function getTypeDeProspect(): ?string
    {
        return $this->typeDeProspect;
    }

    public function setTypeDeProspect(string $typeDeProspect): static
    {
        $this->typeDeProspect = $typeDeProspect;

        return $this;
    }

    public function getKilometrage(): ?float
    {
        return $this->kilometrage;
    }

    public function setKilometrage(float $kilometrage): static
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function setKilometrage1(string $kilometrage): static
    {
        $this->kilometrage = floatval($kilometrage);

        return $this;
    }

    public function getLibelleEnergieEnerg(): ?string
    {
        return $this->libelleEnergieEnerg;
    }

    public function setLibelleEnergieEnerg(?string $libelleEnergieEnerg): static
    {
        $this->libelleEnergieEnerg = $libelleEnergieEnerg;

        return $this;
    }

    public function getVendeurVN(): ?string
    {
        return $this->vendeurVN;
    }

    public function setVendeurVN(?string $vendeurVN): static
    {
        $this->vendeurVN = $vendeurVN;

        return $this;
    }

    public function getVendeurVO(): ?string
    {
        return $this->vendeurVO;
    }

    public function setVendeurVO(?string $vendeurVO): static
    {
        $this->vendeurVO = $vendeurVO;

        return $this;
    }

    public function getCommentaireDeFacturationVeh(): ?string
    {
        return $this->commentaireDeFacturationVeh;
    }

    public function setCommentaireDeFacturationVeh(?string $commentaireDeFacturationVeh): static
    {
        $this->commentaireDeFacturationVeh = $commentaireDeFacturationVeh;

        return $this;
    }

    public function getTypeVNVO(): ?string
    {
        return $this->typeVNVO;
    }

    public function setTypeVNVO(?string $typeVNVO): static
    {
        $this->typeVNVO = $typeVNVO;

        return $this;
    }

    public function getNumeroDeDossierVNVO(): ?string
    {
        return $this->numeroDeDossierVNVO;
    }

    public function setNumeroDeDossierVNVO(?string $numeroDeDossierVNVO): static
    {
        $this->numeroDeDossierVNVO = $numeroDeDossierVNVO;

        return $this;
    }

    public function getIntermediaireDeVenteVN(): ?string
    {
        return $this->intermediaireDeVenteVN;
    }

    public function setIntermediaireDeVenteVN(?string $intermediaireDeVenteVN): static
    {
        $this->intermediaireDeVenteVN = $intermediaireDeVenteVN;

        return $this;
    }

    public function getDateEvenementVeh(): ?\DateTimeInterface
    {
        return $this->dateEvenementVeh;
    }

    public function setDateEvenementVeh(\DateTimeInterface $dateEvenementVeh): static
    {
        $this->dateEvenementVeh = $dateEvenementVeh;

        return $this;
    }

    public function getOrigineEvenementVeh(): ?string
    {
        return $this->origineEvenementVeh;
    }

    public function setOrigineEvenementVeh(string $origineEvenementVeh): static
    {
        $this->origineEvenementVeh = $origineEvenementVeh;

        return $this;
    }
}
