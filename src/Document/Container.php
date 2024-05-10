<?php
    namespace App\Document;

    use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

    #[MongoDB\Document(collection: 'cases-capsules')]
    class Container
    {
        #[MongoDB\Id]
        protected string $id;

        #[MongoDB\Field(type: 'string')]
        protected string $name;

        #[MongoDB\Field(type: 'string')]
        protected string $command;

        #[MongoDB\Field(type: 'float')]
        protected float $price;

        #[MongoDB\Field(type: 'string')]
        protected string $type;

        #[MongoDB\Field(type: 'string')]
        protected string $currType;

        #[MongoDB\Field(type: 'collection')]
        protected array $items;

        public function getId(): string { return $this->id; }
        public function getName(): string { return $this->name; }
        public function getCommand(): string { return $this->command; }
        public function getPrice(): float { return $this->price; }
        public function getType(): string { return $this->type; }
        public function getCurrType(): string { return $this->currType; }
        public function getItems(): array { return $this->items; }

        public function getItemsByPrefix(string $itemPrefix): array
        {
            $items = [];
            foreach ($this->items as $item) {
                if (strpos($item["name"], $itemPrefix) === 0) {
                    $items[] = $item;
                }
            }
            return $items;
        }

        public function getNumberItemsInRarity(string $rarity): int
        {
            $count = 0;
            foreach ($this->items as $item) {
                if ($item["rarity"] === $rarity) {
                    $count++;
                }
            }
            return $count;
        }
    }
