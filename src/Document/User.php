<?php
    namespace App\Document;

    use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

    #[MongoDB\Document(collection: 'users')]
    class User
    {
        #[MongoDB\Field(type: 'int')]
        protected int $casePoints;

        #[MongoDB\Field(type: 'int')]
        protected int $casesOpened;

        #[MongoDB\Field(type: 'string')]
        protected ?string $banType = null;

        #[MongoDB\Field(type: 'string')]
        protected string $dailyClaim;

        #[MongoDB\Field(type: 'int')]
        protected int $dailyStreak;

        #[MongoDB\Field(type: 'string')]
        protected string $hourlyClaim;

        #[MongoDB\Id]
        protected string $id;

        #[MongoDB\Field(type: 'collection')]
        protected array $inventory;

        #[MongoDB\Field(type: 'int')]
        protected int $inventorySize;

        #[MongoDB\Field(type: 'float')]
        protected float $inventoryValue;

        #[MongoDB\Field(type: 'collection')]
        protected array $itemInventory;

        #[MongoDB\Field(type: 'int')]
        protected ?int $knivesOpened = null;

        #[MongoDB\Field(type: 'int')]
        protected ?int $opTickets = null;

        #[MongoDB\Field(type: 'int')]
        protected ?int $operationTokens = null;

        #[MongoDB\Field(type: 'string')]
        protected ?string $premium = null;

        #[MongoDB\Field(type: 'int')]
        protected ?int $redsOpened = null;

        #[MongoDB\Field(type: 'float')]
        protected float $totalSpending;

        #[MongoDB\Field(type: 'string')]
        protected string $userID;

        #[MongoDB\Field(type: 'string')]
        protected string $username;

        #[MongoDB\Field(type: 'float')]
        protected float $wallet;

        #[MongoDB\Field(type: 'int')]
        protected ?int $compOp = null;

        public function getBanType(): ?string { return $this->banType; }
        public function getCasePoints(): int { return $this->casePoints; }
        public function getCasesOpened(): int { return $this->casesOpened; }
        public function getCompOp(): ?int { return $this->compOp; }
        public function getDailyClaim(): string { return $this->dailyClaim; }
        public function getDailyStreak(): int { return $this->dailyStreak; }
        public function getHourlyClaim(): string { return $this->hourlyClaim; }
        public function getId(): string { return $this->id; }
        public function getInventory(): array { return $this->inventory; }
        public function getInventorySize(): int { return $this->inventorySize; }
        public function getInventoryValue(): float { return $this->inventoryValue; }
        public function getItemInventory(): array { return $this->itemInventory; }
        public function getKnivesOpened(): ?int { return $this->knivesOpened; }
        public function getOperationTokens(): ?int { return $this->operationTokens; }
        public function getOpTickets(): ?int { return $this->opTickets; }
        public function getPremium(): ?string { return $this->premium; }
        public function getRedsOpened(): ?int { return $this->redsOpened; }
        public function getTotalSpending(): float { return $this->totalSpending; }
        public function getUsername(): string { return $this->username; }
        public function getUserID(): string { return $this->userID; }
        public function getWallet(): float { return $this->wallet; }
    }
