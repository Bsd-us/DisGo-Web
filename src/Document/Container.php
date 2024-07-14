<?php
    namespace App\Document;

    use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

    #[MongoDB\Document(collection: 'cases-capsules')]
    class Container
    {
        #[MongoDB\Id]
        protected string $id;

        #[MongoDB\Field(type: 'string')]
        protected string $command;

        #[MongoDB\Field(type: 'string')]
        protected string $currType;

        #[MongoDB\Field(type: 'collection')]
        protected array $items;

        #[MongoDB\Field(type: 'string')]
        protected string $name;

        #[MongoDB\Field(type: 'float')]
        protected float $price;

        #[MongoDB\Field(type: 'string')]
        protected string $type;

        public function getId(): string { return $this->id; }
        public function getCommand(): string { return $this->command; }
        public function getCurrType(): string { return $this->currType; }
        public function getItems(): array { return $this->items; }
        public function getName(): string { return $this->name; }
        public function getPrice(): float { return $this->price; }
        public function getType(): string { return $this->type; }
    }
