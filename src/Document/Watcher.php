<?php
    namespace App\Document;

    use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

    #[MongoDB\Document(collection: 'user-watchers')]
    class Watcher
    {
        #[MongoDB\Id]
        protected string $id;

        #[MongoDB\Field(type: 'collection')]
        protected ?array $operationQuests = null;

        #[MongoDB\Field(type: 'string')]
        protected string $userID;

        #[MongoDB\Field(type: 'string')]
        protected string $username;

        public function getId(): string { return $this->id; }
        public function getOperationQuests(): ?array { return $this->operationQuests; }
        public function getUserID(): string { return $this->userID; }
        public function getUsername(): string { return $this->username; }
    }
