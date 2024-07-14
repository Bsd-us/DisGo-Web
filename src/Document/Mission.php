<?php
    namespace App\Document;

    use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

    #[MongoDB\Document(collection: 'all-missions')]
    class Mission
    {
        #[MongoDB\Id]
        protected string $id;

        #[MongoDB\Field(type: 'float')]
        protected float $completion;

        #[MongoDB\Field(type: 'string')]
        protected string $difficulty;

        #[MongoDB\Field(type: 'string')]
        protected string $missionText1;

        #[MongoDB\Field(type: 'string')]
        protected string $missionText2;

        #[MongoDB\Field(type: 'string')]
        protected string $type;

        #[MongoDB\Field(type: 'string')]
        protected string $value;

        public function getId(): string { return $this->id; }
        public function getCompletion(): float { return $this->completion; }
        public function getDifficulty(): string { return $this->difficulty; }
        public function getMissionText1(): string { return $this->missionText1; }
        public function getMissionText2(): string { return $this->missionText2; }
        public function getType(): string { return $this->type; }
        public function getValue(): string { return $this->value; }
    }
