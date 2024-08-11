<?php
    namespace App\Document;

    use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
    use Symfony\Component\Validator\Constraints as Assert;

    #[MongoDB\Document(collection: 'marketitems')]
    class MarketItem
    {
        #[MongoDB\Field(type: 'string')]
        protected string $dateListed;

        #[MongoDB\Id]
        protected string $id;

        #[MongoDB\Field(type: 'collection')]
        protected array $item;

        #[MongoDB\Field(type: 'string')]
        protected string $owner;

        #[MongoDB\Field(type: 'string')]
        protected string $ownerID;

        #[MongoDB\Field(type: 'float')]
        protected float $sellPrice;

        public function getDateListed(): string { return $this->dateListed; }
        public function getId(): string { return $this->id; }
        public function getItem(): array { return $this->item; }
        public function getOwner(): string { return $this->owner; }
        public function getOwnerID(): string { return $this->ownerID; }
        public function getSellPrice(): float { return $this->sellPrice; }
    }
