<?php
    namespace App\Document;

    use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

    #[MongoDB\Document(collection: 'users')]
    class User
    {
        #[MongoDB\Id]
        protected string $id;

        #[MongoDB\Field(type: 'collection')]
        protected array $inventory;

        #[MongoDB\Field(type: 'string')]
        protected string $userID;

        #[MongoDB\Field(type: 'string')]
        protected string $username;

        #[MongoDB\Field(type: 'float')]
        protected float $wallet;

        #[MongoDB\Field(type: 'float')]
        protected float $inventoryValue;

        #[MongoDB\Field(type: 'int')]
        protected int $inventorySize;

        #[MongoDB\Field(type: 'string')]
        protected string $dailyClaim;

        #[MongoDB\Field(type: 'int')]
        protected int $casesOpened;

        #[MongoDB\Field(type: 'float')]
        protected float $totalSpending;

        #[MongoDB\Field(type: 'int')]
        protected int $redsOpened;

        #[MongoDB\Field(type: 'int')]
        protected int $knivesOpened;

        #[MongoDB\Field(type: 'int')]
        protected int $casePoints;

        #[MongoDB\Field(type: 'string')]
        protected ?string $premium = null;

        public function getId(): string { return $this->id; }
        public function getInventory(): array { return $this->inventory; }
        public function getUsername(): string { return $this->username; }
        public function getUserID(): string { return $this->userID; }
        public function getWallet(): float { return $this->wallet; }
        public function getInventoryValue(): float { return $this->inventoryValue; }
        public function getInventorySize(): int { return $this->inventorySize; }
        public function getDailyClaim(): string { return $this->dailyClaim; }
        public function getCasesOpened(): int { return $this->casesOpened; }
        public function getTotalSpending(): float { return $this->totalSpending; }
        public function getRedsOpened(): int { return $this->redsOpened; }
        public function getKnivesOpened(): int { return $this->knivesOpened; }
        public function getCasePoints(): int { return $this->casePoints; }
        public function getPremium(): ?string { return $this->premium; }
    }
